<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\DBUserRepository;

use Universibo\Bundle\LegacyBundle\Entity\DBCollaboratoreRepository;

class DBCollaboratoreRepositoryTest extends DBRepositoryTest
{
    /**
     * @var DBCollaboratoreRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();
        $this->repository = new DBCollaboratoreRepository($this->db, new DBUserRepository($this->db));
    }

    public function testFind()
    {
        $collaboratore = $this->repository->find(81);

        $this->assertEquals(81, $collaboratore->getIdUtente());
        $this->assertEquals('3381407176', $collaboratore->getRecapito());
        $this->assertEquals('81_brain.jpg', $collaboratore->getFotoFilename());
        $this->assertEquals('fondatore - progettista software', $collaboratore->getRuolo());
    }
}