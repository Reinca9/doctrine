<?php
namespace Tests\App;

use App\MySQLQueryBuilder;
use PHPUnit\Framework\TestCase;

class MySQLQueryBuilderTest extends TestCase
{
    /**
     *
     * @var MySQLQueryBuilder
     */
    private $builder;

    public function setUp(): void
    {
        $this->builder = new MySQLQueryBuilder();
    }

    public function testSelect()
    {
        $req = $this->builder->select(['*'], 'user')
                        ->getSQL();

        $this->assertEquals('SELECT * FROM user;', $req);
    }

    public function testReset()
    {
        $req = $this->builder->select(['*'], 'user');
        $req = $this->builder->update('user')
                            ->set(['nom' => ':nom'])
                            ->getSQL();

        $this->assertEquals('UPDATE user SET nom = :nom;', $req);
    }

    public function testSelectWithWhereAndGroup()
    {
        $req = $this->builder->select(['*'], 'user')
                                ->where('ID', ':id')
                                ->group('nom')
                                ->getSQL();

        $this->assertEquals('SELECT * FROM user WHERE ID = :id GROUP BY nom ASC;', $req);
    }
}