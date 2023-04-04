<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

    public function __construct(
        private SluggerInterface $slugger,
        private $userImgDirectory,
        private $csvDirectory
    ){}

    public function upload(UploadedFile $file): string
    {
        $origFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($origFileName);
        $extension = $file->guessExtension();
        $newFileName = $safeFileName.'-'.uniqid().'.'.$extension;
        if($extension === 'csv'){
            $this->storeCsv($file, $newFileName);
        }
        if($extension === 'jpg' || $extension === 'png'){
            $this->storeUserImg($file, $newFileName);
        }
        return $newFileName;
    }
    private function storeCsv($file, $fileName): void
    {
        try{
            $file->move($this->getCsvDirectory(), $fileName);
        } catch (FileException $e){
            var_dump($e);
            die();
        }
    }
    private function storeUserImg(UploadedFile $file, string $fileName): void
    {
        try{
            $file->move($this->getUserImgDirectory(), $fileName);
        } catch (FileException $e){
            var_dump($e);
            die();
        }
    }

    public function getUserImgDirectory(): string
    {
        return $this->userImgDirectory;
    }

    /**
     * @return mixed
     */
    public function getCsvDirectory():string
    {
        return $this->csvDirectory;
    }

}