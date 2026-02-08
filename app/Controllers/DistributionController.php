<?php
namespace App\Controllers;

class DistributionController extends BaseController
{
    public function index()
    {
        $sessionData = $this->getSessionData();
        $data = array_merge($sessionData, [
            'title' => 'Distribution',
        ]);

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        if ($redirect = $this->redirectIfNotOwnerAndAdmin()) {
            return $redirect;
        }

        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Distribution/Distribution', $data) .
            view('Template/Footer');
    }

    public function getDistributionByDate()
    {

        $date = date('Y-m-d');
        
        $distributionData = $this->distributionModel->getDistributionByDate($date);

        if (!$distributionData) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'error' => 'No distribution records found for the specified date'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Distribution records retrieved successfully',
            'data' => $distributionData
        ]);
    }

    public function addDistribution()
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        // Validate required fields
        if (!isset($data->product_id, $data->product_qnty, $data->distribution_date)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required fields']);
        }

        // Insert distribution record
        $insertData = [
            'product_id' => $data->product_id,
            'product_qnty' => $data->product_qnty,
            'distribution_date' => $data->distribution_date,
        ];

        try {
            $this->distributionModel->insert($insertData);
            return $this->response->setJSON(['message' => 'Distribution record added successfully']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to add distribution record']);
        }
    }

    public function deleteDistribution($id)
    {
        try {
            $this->distributionModel->delete($id);
            return $this->response->setJSON(['message' => 'Distribution record deleted successfully']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete distribution record']);
        }
    }

    public function updateDistribution($id)
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        // Validate required fields
        if (!isset($data->product_id, $data->product_qnty, $data->distribution_date)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required fields']);
        }

        // Update distribution record
        $updateData = [
            'product_id' => $data->product_id,
            'product_qnty' => $data->product_qnty,
            'distribution_date' => $data->distribution_date,
        ];

        try {
            $this->distributionModel->update($id, $updateData);
            return $this->response->setJSON(['message' => 'Distribution record updated successfully']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update distribution record']);
        }
    }
}