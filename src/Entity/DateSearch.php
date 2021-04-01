
<?php
namespace App\Entity;
class DateSearch
{
    /**
     * @var \DateTime|null
     */
    private $dateS;
    /**
     * @var \DateTime|null
     */
    private $dateE;

    /**
     * @return \DateTime|null
     */
    public function getDateS(): ?\DateTime
    {
        return $this->dateS;
    }

    /**
     * @param \DateTime|null $dateS
     */
    public function setDateS(?\DateTime $dateS): void
    {
        $this->dateS = $dateS;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateE(): ?\DateTime
    {
        return $this->dateE;
    }

    /**
     * @param \DateTime|null $dateE
     */
    public function setDateE(?\DateTime $dateE): void
    {
        $this->dateE = $dateE;
    }
}