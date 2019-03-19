<?php
namespace Salesforce\ORM\Query;

class Builder
{
    private $select = [];
    private $from;
    private $where = [];
    private $limit;

    /**
     * @param array|string $select select
     * @return $this
     */
    public function select($select)
    {
        if (is_array($select)) {
            $this->select += $select;
        } else {
            $this->select[] = $select;
        }

        return $this;
    }

    /**
     * @param string $objectType object type
     * @return $this
     */
    public function from($objectType)
    {
        $this->from = $objectType;

        return $this;
    }

    /**
     * @param string|array $predicate where
     * @return $this
     */
    public function where($predicate)
    {
        if (is_array($predicate) && !empty($predicate)) {
            $this->where = $predicate;
        } else {
            $this->where[] = $predicate;
        }

        return $this;
    }

    /**
     * @param string $predicate where
     * @return $this
     */
    public function andWhere(string $predicate)
    {
        $this->where[] = ' AND ' . $predicate;

        return $this;
    }

    /**
     * @param string $predicate where
     * @return $this
     */
    public function orWhere(string $predicate)
    {
        $this->where[] = ' OR ' . $predicate;

        return $this;
    }

    /**
     * @param  int|null $limit limit
     * @return $this
     */
    public function limit($limit = null)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        $query = 'SELECT ' . implode(',', $this->select) . ' FROM ' . $this->from;

        if (count($this->where) > 0) {
            $query .= $this->getWhereString();
        }

        if (null !== $this->limit) {
            $query .= ' LIMIT ' . $this->limit;
        }

        return $query;
    }

    /**
     * @return string|null
     */
    private function getWhereString()
    {
        if (empty($this->where)) {
            return null;
        }

        $whereString = ' WHERE ';
        foreach ($this->where as $where) {
            preg_match('/(AND |OR )?(\w+) *([!=><]+|LIKE) *[\'"]?(.*)[\'"]?/i', $where, $matches);
            list($all, $connector, $field, $operator, $value) = $matches;
            $whereString .= $connector . $field . $operator . $this->quote($value) . ' ';
        }

        return rtrim($whereString);
    }

    /**
     * @param mixed $value value
     * @return string
     */
    private function quote($value)
    {
        if ($value == 'null') {
            return $value;
        }

        return '\'' . $value . '\'';
    }
}
