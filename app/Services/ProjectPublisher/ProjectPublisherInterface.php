<?php


namespace App\Services\ProjectPublisher;

/**
 * Define the public API for the project publisher class
 *
 * Class ProjectPublisherInterface
 * @package App\Services\ProjectPublisher
 */
interface ProjectPublisherInterface
{
    /*
     * Pass in a project ID to publish a project. This will
     * create a index.html file with all the project data in
     * the head and push the file amazon s3.
     */
    public function publish(Int $id);

    /*
     * Pass in a project id to unpublish a project. This removes
     * the index.html file from amazon s3.
     */
    public function unpublish(Int $id);

    /**
     * Returns the blade html view for a project. On publish this
     * is pushed to s3, on the preview route this returned to
     * the browser by the controller.
     *
     * @param array $data
     * @param bool $playing
     * @param String $playerEnv
     * @return mixed
     */
    public function view(Array $data, Bool $playing, String $playerEnv);


}