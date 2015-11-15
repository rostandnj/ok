<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Picture
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\ATIBundle\Entity\PictureRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Picture
{
    
	
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="addr", type="string", length=255)
     */
    private $addr;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set addr
     *
     * @param string $addr
     * @return Picture
     */
    public function setAddr($addr)
    {
        $this->addr = $addr;
    
        return $this;
    }

    /**
     * Get addr
     *
     * @return string 
     */
    public function getAddr()
    {
        return $this->addr;
    }

    private $tempFilename;

    private $file;

    public function getFile()
    {
        return $this->file;
    }
    public function setFile(UploadedFile $file)
    {
       $this->file = $file;

        if (null !== $this->addr) 
        {   // On sauvegarde l'extension du fichier pour le supprimer plus tard

            $this->tempFilename = $this->addr;
            // On réinitialise les valeurs des attributs url et alt
            $this->addr= null;
        }
    }

    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */

    public function preUpload()

    {

        // Si jamais il n'y a pas de fichier (champ facultatif)

        if (null === $this->file) 
        {
            return;
        }


    // Le nom du fichier est son id, on doit juste stocker également son extension

    // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »

     $this->addr = $this->file->guessExtension();


    // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute


    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */

    public function upload()

    {

        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) 
        {
            return;
        }


        // Si on avait un ancien fichier, on le supprime

        if (null !== $this->tempFilename) 
        {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;

            if (file_exists($oldFile)) 
            {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix

        $this->file->move($this->getUploadRootDir(), // Le répertoire de destination
        $this->id.'.'.$this->addr   // Le nom du fichier à créer, ici « id.extension »
        );
    }


    /**
    * @ORM\PreRemove()
    */

    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->addr;
    }


    /**
    * @ORM\PostRemove()
    */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) 
        {   // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getAddr();
    }
}
