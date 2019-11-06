<?php

defined('BASEPATH') or exit('No direct script access allowed');

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

class Vision extends CI_Controller
{

    public function index()
    {
        $data = [
            'datas' => $this->db->get('datas')->result()
        ];
        $this->load->view('vision_index', $data);
    }

    public function show()
    {
        $id = $this->uri->segment(3);
        $data = [
            'data' => $this->db->get_where('datas', ['id' => $id])->row(),
            'datas' => $this->db->get('datas')->result()
        ];
        $this->load->view('vision_index', $data);
    }

    public function dosomemagic()
    {
        $config['upload_path']          = '././img/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['encrypt_name']         = true;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('img')) {
            $error = array('error' => $this->upload->display_errors());

            var_dump($error);
        } else {
            $data = $this->upload->data();
            $fileToUpload = $data['file_name'];

            echo FCPATH . 'vendor/autoload.php';
            var_dump($data['file_type']. 'a');

            echo '<br>';
            echo '<br>';

            $connectionString = "DefaultEndpointsProtocol=https;AccountName=rifqiblob;AccountKey=t/Ed4rbod65ilRgjSkrNuxt7A5EoWsT8SEI916+TSirhGzYdJyOO2CrAcHO80Gpdi+hT9YQmpxV9FngpULpdAQ==;EndpointSuffix=core.windows.net";

            // Create blob client.
            $blobClient = BlobRestProxy::createBlobService($connectionString);

            // Create container options object.
            $createContainerOptions = new CreateContainerOptions();

            $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

            // Set container metadata.
            $createContainerOptions->addMetaData("key1", "value1");
            $createContainerOptions->addMetaData("key2", "value2");

            $containerName = "img";

            echo "Uploading BlockBlob: " . PHP_EOL;
            echo $fileToUpload;
            echo "<br />";

            $content = fopen($data['full_path'], "r");

            $options = new CreateBlockBlobOptions();
            $options->setContentType($data['file_type']);
            //Upload blob

            try {
                $blobClient->createBlockBlob($containerName, $fileToUpload, $content, $options);
            } catch (ServiceException $e) {
                // Handle exception based on error codes and messages.
                // Error codes and messages are here:
                // http://msdn.microsoft.com/library/azure/dd179439.aspx
                $code = $e->getCode();
                $error_message = $e->getMessage();
                echo $code . ": " . $error_message . "<br />";
            } catch (InvalidArgumentTypeException $e) {
                // Handle exception based on error codes and messages.
                // Error codes and messages are here:
                // http://msdn.microsoft.com/library/azure/dd179439.aspx
                $code = $e->getCode();
                $error_message = $e->getMessage();
                echo $code . ": " . $error_message . "<br />";
            } finally {
                $this->db->insert('datas', ['img' => $fileToUpload]);
                $last_row = $this->db->select('id')->order_by('id', "desc")->limit(1)->get('datas')->row();
                redirect(base_url('vision/show/' . $last_row->id));
            }
        }
    }
}
