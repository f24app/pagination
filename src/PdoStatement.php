<?php

namespace SoampliApps\Pagination;

class PdoStatement extends \PdoStatement
{
    // TODO: override any other methods that might be needed; the magic __call function can't help us here, because the parent has the methods
    protected $pagination = null;
    protected $countStatement;
    protected $primaryStatement;

    protected $params = array();
    protected $cols = array();

    public function setPagination(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;
    }

    public function setPrimaryStatement(\PdoStatement $primary_statement)
    {
        $this->primaryStatement = $primary_statement;
    }

    public function setCountStatement(\PdoStatement $count_statement)
    {
        $this->countStatement = $count_statement;
    }

    public function bindParam($parameter, &$variable, $data_type = \PDO::PARAM_STR, $length = 0, $driver_options = array())
    {
        $this->countStatement->bindParam($parameter, $variable, $data_type, $length, $driver_options);
        $this->params[$parameter] = $variable;

        return $this->primaryStatement->bindParam($parameter, $variable, $data_type, $length, $driver_options);
    }

    public function bindColumn($column, &$param, $type = \PDO::PARAM_STR, $maxlen = 0, $driverdata = null)
    {
        $this->countStatement->bindColumn($column, $param, $type, $maxlen, $driverdata);
        $this->cols[$column] = $param;

        return $this->primaryStatement->bindColumn($column, $param, $type, $maxlen, $driverdata);
    }

    public function execute($input_parameters = null)
    {
        $this->countStatement->execute($input_parameters);

        return $this->primaryStatement->execute($input_parameters);
    }

    public function fetch($how = null, $orientation = null, $offset = null)
    {
        $this->countStatement->fetch($how, $orientation, $offset);

        return $this->primaryStatement->fetch($how, $orientation, $offset);
    }

    public function getPaginationTotalCount()
    {
        $this->countStatement->execute();

        while ($row = $this->countStatement->fetch(\PDO::FETCH_ASSOC)) {
            return $row['pagination_count'];
        }

        return 0;
    }

    public function getPaginationWithTotalCount()
    {
        if (!is_null($this->pagination)) {
            $this->pagination->setTotalResults($this->getPaginationTotalCount());
        }

        return $this->pagination;
    }
}
