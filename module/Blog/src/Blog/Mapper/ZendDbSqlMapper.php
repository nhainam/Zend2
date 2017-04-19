<?php
// Filename: /module/Blog/src/Blog/Mapper/ZendDbSqlMapper.php
namespace Blog\Mapper;

use Blog\Model\PostInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydrationInterface;

class ZendDbSqlMapper implements PostMapperInterface
{
    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    /**
     * @var \Zend\Stdlib\Hydrator\HydrationInterface
     */
    protected $hydrator;

    /**
     * @var \Blog\Model\PostInterface
     */
    protected $postPrototype;

    /**
     * @param AdapterInterface $dbAdapter
     */
    public function __construct(
        AdapterInterface $adapterInterface,
        HydrationInterface $hydrationInterface,
        PostInterface $postInterface
    ) {
        $this->dbAdapter    = $adapterInterface;
        $this->hydrator     = $hydrationInterface;
        $this->postPrototype= $postInterface;
    }

    /**
     * @param init|string $id
     *
     * @return PostInterface
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        $sql        = new Sql($this->dbAdapter);
        $select     = $sql->select('posts');
        $select->where(['id = ?'=> $id]);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->postPrototype);
        }
        throw new \InvalidArgumentException("Blog with given ID:{$id} not found.");
    }

    /**
     * @return array|PostInterface
     */
    public function findAll()
    {
        $sql        = new Sql($this->dbAdapter);
        $select     = $sql->select('posts');

        $stmt       = $sql->prepareStatementForSqlObject($select);
        $result     = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);

            return $resultSet->initialize($result);
        }
        return [];
    }

    /**
     * @param PostInterface $postObject
     *
     * @return PostInterface
     * @throws \Exception
     */
    public function save(PostInterface $postObject)
    {
        $postData = $this->hydrator->extract($postObject);
        unset($postData['id']);

        if ($postObject->getId()) {
            $action = new Update('posts');
            $action->set($postData);
            $action->where(['id = ?' => $postObject->getId()]);
        } else {
            $action = new Insert('posts');
            $action->values($postData);
        }

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newId = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $postObject->setId($newId);
            }

            return $postObject;
        }

        throw new \Exception('Database error');
    }

    /**
     * @param PostInterface $postObject
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(PostInterface $post)
    {
        $action = new Delete('posts');
        $action->where(['id = ?' => $post->getId()]);

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }
}