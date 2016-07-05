<?php
/**
 * CoreShop.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2016 Dominik Pfaffenbauer (http://www.pfaffenbauer.at)
 * @license    http://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Model\Dao;

use CoreShop\Exception;
use CoreShop\Model\AbstractModel;
use Pimcore\Model\Object\AbstractObject;
use Pimcore\Model\Dao;

/**
 * Class AbstractDao
 * @package CoreShop\Model\Dao
 */
abstract class AbstractDao extends Dao\AbstractDao
{
    /**
     * @var string
     */
    protected $tableName = '';

    /**
     * Get table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Get Object by id.
     *
     * @param null $id
     *
     * @throws Exception
     */
    public function getById($id = null)
    {
        if ($id != null) {
            $this->model->setId($id);
        }

        $data = $this->db->fetchRow('SELECT * FROM '.$this->getTableName().' WHERE id = ?', $this->model->getId());

        if (!$data['id']) {
            throw new Exception(get_class($this->model).' with the ID '.$this->model->getId()." doesn't exists");
        }

        $this->assignVariablesToModel($data);
        $this->getData();
    }

    /**
     * Get Object by field.
     *
     * @param string $field
     * @param string $value
     *
     * @throws Exception
     */
    public function getByField($field, $value)
    {
        $data = $this->db->fetchRow('SELECT * FROM '.$this->getTableName()." WHERE $field = ?", $value);

        if (!$data['id']) {
            throw new Exception(get_class($this->model).' with the field/value '.$field.'-'.$value." doesn't exists");
        }

        $this->assignVariablesToModel($data);
        $this->getData();
    }

    /**
     * Get the data-elements for the object from database for the given path.
     */
    public function getData()
    {
        if ($this->model->getLocalizedFields()) {
            $this->model->getLocalizedFields()->load();
        }
    }

    /**
     * Save object.
     *
     * @throws \Zend_Db_Adapter_Exception
     */
    public function save()
    {
        $vars = get_object_vars($this->model);

        $buffer = array();

        $validColumns = $this->getValidTableColumns($this->getTableName());

        if (count($vars)) {
            foreach ($vars as $k => $v) {
                if (!in_array($k, $validColumns)) {
                    continue;
                }

                $getter = 'get'.ucfirst($k);

                if (!is_callable(array($this->model, $getter))) {
                    continue;
                }

                $value = $this->model->$getter();

                if (is_bool($value)) {
                    $value = (int) $value;
                }
                if (is_array($value)) {
                    $value = serialize($value);
                }
                if ($value instanceof AbstractObject) {
                    $value = $value->getId();
                }
                if ($value instanceof AbstractModel) {
                    $value = $value->getId();
                }
                if (is_object($value)) {
                    $value = serialize($value);
                }

                $buffer[$k] = $value;
            }
        }

        if ($this->model->getId() !== null) {
            $this->db->update($this->getTableName(), $buffer, $this->db->quoteInto('id = ?', $this->model->getId()));

            if ($this->model->getLocalizedFields()) {
                $this->model->getLocalizedFields()->save();
            }

            return;
        }

        $this->db->insert($this->getTableName(), $buffer);
        $this->model->setId($this->db->lastInsertId());

        if ($this->model->getLocalizedFields()) {
            $this->model->getLocalizedFields()->save();
        }
    }

    /**
     * Delete Object.
     */
    public function delete()
    {
        $this->db->delete($this->getTableName(), $this->db->quoteInto('id = ?', $this->model->getId()));
    }
}
