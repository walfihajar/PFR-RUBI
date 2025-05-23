<?php


namespace App\Services;

use App\Models\Donation;
use App\Repositories\Interfaces\DonationRepositoryInterface;
use App\Repositories\Interfaces\SerologyRepositoryInterface;
use App\Repositories\Interfaces\ObservationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class DonationService
{
    protected DonationRepositoryInterface $donationRepository;
    protected SerologyRepositoryInterface $serologyRepository;
    protected ObservationRepositoryInterface $observationRepository;

    public function __construct(
        DonationRepositoryInterface    $donationRepository,
        SerologyRepositoryInterface    $serologyRepository,
        ObservationRepositoryInterface $observationRepository
    )
    {
        $this->donationRepository = $donationRepository;
        $this->serologyRepository = $serologyRepository;
        $this->observationRepository = $observationRepository;
    }

    public function getAllDonations(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->donationRepository->getAllDonations($filters, $perPage);
    }

    public function getDonationById(int $id): ?Donation
    {
        return $this->donationRepository->getDonationWithRelations($id, ['user', 'serology', 'observations']);
    }

    public function getDonationsByUserId($userId, $perPage = 6)
    {
        return Donation::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }

    public function createDonation(array $data): Donation
    {
        if (!isset($data['identifier'])) {

            try {
                $year = date('Y');
                $nextVal = \DB::selectOne("SELECT nextval('donation_identifier_seq') as next_val")->next_val;
                $data['identifier'] = 'DON-' . $year . '-' . str_pad($nextVal, 3, '0', STR_PAD_LEFT);
            } catch (\Exception $e) {
                \DB::statement("CREATE SEQUENCE IF NOT EXISTS donation_identifier_seq");
                $nextVal = \DB::selectOne("SELECT nextval('donation_identifier_seq') as next_val")->next_val;
                $data['identifier'] = 'DON-' . $year . '-' . str_pad($nextVal, 3, '0', STR_PAD_LEFT);
            }
        }

        try {
            $donation = $this->donationRepository->createDonation($data);
        } catch (\Illuminate\Database\QueryException $e) {
            if (strpos($e->getMessage(), 'donations_identifier_unique') !== false) {
                $year = date('Y');
                $nextVal = \DB::selectOne("SELECT nextval('donation_identifier_seq') as next_val")->next_val;
                $data['identifier'] = 'DON-' . $year . '-' . str_pad($nextVal, 3, '0', STR_PAD_LEFT);
                $donation = $this->donationRepository->createDonation($data);
            } else {
                throw $e;
            }
        }

        if (isset($data['serology'])) {
            $serologyData = $data['serology'];
            $serologyData['donation_id'] = $donation->id;

            if (!isset($serologyData['result'])) {
                $serologyData['result'] = $this->determineSerologyResult($serologyData);
            }

            $this->serologyRepository->createSerology($serologyData);
        }

        if (isset($data['observations']) && is_array($data['observations'])) {
            foreach ($data['observations'] as $observationData) {
                $observationData['donation_id'] = $donation->id;
                $this->observationRepository->createObservation($observationData);
            }
        }

        return $donation;
    }

    public function updateDonation(int $id, array $data): bool
    {
        $result = $this->donationRepository->updateDonation($id, $data);


        if (isset($data['serology'])) {
            $serologyData = $data['serology'];
            $serology = $this->serologyRepository->getSerologyByDonationId($id);

            if ($serology) {
                if (!isset($serologyData['result'])) {
                    $serologyData['result'] = $this->determineSerologyResult($serologyData);
                }

                $this->serologyRepository->updateSerology($serology->id, $serologyData);
            } else {
                $serologyData['donation_id'] = $id;

                if (!isset($serologyData['result'])) {
                    $serologyData['result'] = $this->determineSerologyResult($serologyData);
                }

                $this->serologyRepository->createSerology($serologyData);
            }
        }


        if (isset($data['observations']) && is_array($data['observations'])) {

            $existingObservations = $this->observationRepository->getObservationsByDonationId($id);
            foreach ($existingObservations as $observation) {
                $this->observationRepository->deleteObservation($observation->id);
            }

            foreach ($data['observations'] as $observationData) {
                $observationData['donation_id'] = $id;
                $this->observationRepository->createObservation($observationData);
            }
        }

        return $result;
    }

    public function deleteDonation(int $id): bool
    {
        return $this->donationRepository->deleteDonation($id);
    }

    public function getLatestDonations(int $limit = 5): Collection
    {
        return $this->donationRepository->getLatestDonations($limit);
    }

    private function determineSerologyResult(array $serologyData): string
    {
        // Si l'un des tests est positif, le résultat global est positif
        if (
            (isset($serologyData['tpha']) && $serologyData['tpha'] === 'positive') ||
            (isset($serologyData['hb']) && $serologyData['hb'] === 'positive') ||
            (isset($serologyData['hc']) && $serologyData['hc'] === 'positive') ||
            (isset($serologyData['vih']) && $serologyData['vih'] === 'positive')
        ) {
            return 'positive';
        }

        if (
            (isset($serologyData['tpha']) && $serologyData['tpha'] === 'negative') &&
            (isset($serologyData['hb']) && $serologyData['hb'] === 'negative') &&
            (isset($serologyData['hc']) && $serologyData['hc'] === 'negative') &&
            (isset($serologyData['vih']) && $serologyData['vih'] === 'negative')
        ) {
            return 'negative';
        }

        return 'pending';
    }

    public function isDonationEligible(Donation $donation): bool
    {
        if ($donation->serology && $donation->serology->result === 'negative') {
            return true;
        }

        return false;
    }
}
