<?php

/**
 * This file is a part of the FTP Wrapper package
 *
 * For the full informations, please read the README file
 * distributed with this source code
 *
 * @package FTP Wrapper
 * @version 1.1.0
 * @author  Touki <g.vincendon@vithemis.com>
 */

namespace Touki\FTP;

use Touki\FTP\Manager\FTPFilesystemManager;

/**
 * Factory class for FTP
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTPFactory
{
    protected $wrapper;
    protected $manager;
    protected $dlVoter;
    protected $ulVoter;
    protected $crVoter;
    protected $deVoter;

    /**
     * Get Wrapper
     *
     * @return FTPWrapper An FTPWrapper instance
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * Set Wrapper
     *
     * @param FTPWrapper $wrapper FTP Wrapper
     */
    public function setWrapper(FTPWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    /**
     * Get Manager
     *
     * @return FTPFilesystemManager A FilesystemManager instance
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set Manager
     *
     * @param FTPFilesystemManager $manager Filesystem Manager
     */
    public function setManager(FTPFilesystemManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get DownloaderVoter
     *
     * @return DownloaderVoterInterface A Downloader voter
     */
    public function getDownloaderVoter()
    {
        return $this->dlVoter;
    }

    /**
     * Set Downloader Voter
     *
     * @param DownloaderVoterInterface $dlVoter A Downloader voter
     */
    public function setDownloaderVoter(DownloaderVoterInterface $dlVoter)
    {
        $this->dlVoter = $dlVoter;
    }

    /**
     * Get UploaderVoter
     *
     * @return UploaderVoterInterface An Uploader voter
     */
    public function getUploaderVoter()
    {
        return $this->ulVoter;
    }

    /**
     * Set Uploader Voter
     *
     * @param UploaderVoterInterface $ulVoter An Uploader voter
     */
    public function setUploaderVoter(UploaderVoterInterface $ulVoter)
    {
        $this->ulVoter = $ulVoter;
    }

    /**
     * Get CreatorVoter
     *
     * @return CreatorVoter A Creator Voter
     */
    public function getCreatorVoter()
    {
        return $this->crVoter;
    }

    /**
     * Set Creator Voter
     *
     * @param CreatorVoterInterface $crVoter A Creator voter
     */
    public function setCreatorVoter(CreatorVoterInterface $crVoter)
    {
        $this->crVoter = $crVoter;
    }

    /**
     * Get DeleterVoter
     *
     * @return DeleterVoter A Deleter Voter
     */
    public function getDeleterVoter()
    {
        return $this->deVoter;
    }

    /**
     * Set Deleter Voter
     *
     * @param DeleterVoterInterface $deVoter A Deleter voter
     */
    public function setDeleterVoter(DeleterVoterInterface $deVoter)
    {
        $this->deVoter = $deVoter;
    }

    /**
     * Creates an FTP instance
     *
     * @return FTP An FTP instance
     */
    public function build(ConnectionInterface $connection)
    {
        if (!$connection->isConnected()) {
            $connection->open();
        }

        $this->wrapper = new FTPWrapper($connection);

        $factory = new FilesystemFactory(new PermissionsFactory);
        $this->manager = new FTPFilesystemManager($this->wrapper, $factory);

        $this->dlVoter = new DownloaderVoter;
        $this->dlVoter->addDefaultFTPDownloaders($this->wrapper);

        $this->ulVoter = new UploaderVoter;
        $this->ulVoter->addDefaultFTPUploaders($this->wrapper);

        $this->crVoter = new CreatorVoter;
        $this->crVoter->addDefaultFTPCreators($this->wrapper, $this->manager);

        $this->deVoter = new DeleterVoter;
        $this->deVoter->addDefaultFTPDeleters($this->wrapper, $this->manager);

        return new FTP($this->manager, $this->dlVoter, $this->ulVoter, $this->crVoter, $this->deVoter);
    }
}
