<?php
namespace SoampliApps\Pagination;

trait PdoTrait
{
    protected $pagination = null;

    /**
     * Prepare a statement with pagination
     * @param string $statement
     * @param $pagination
     * @param array $driver_options
     * @return \PdoStatement
     */
    public function prepareWithPagination($statement, PaginationInterface $pagination, $driver_options = [])
    {
        $this->pagination = $pagination;

        return $this->prepare($statement, $driver_options);
    }

    /**
     * Get the pagination object
     * @return object
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * Prepare a statement
     * @param string $statement the SQL query
     * @param array $driver_options
     * @return \PdoStatement
     */
    public function prepare($statement, $driver_options = [])
    {
        if (!is_null($this->pagination)) {
            $pagination_query = $this->paginateQuery($statement, $this->pagination->getCurrentPageNumber(), $this->pagination->getMaxResultsPerPage());
            $count_query = $this->convertToCountQuery($statement);

            $primary_statement = parent::prepare($pagination_query, $driver_options);
            $count_statement = parent::prepare($count_query, $driver_options);

            $statement = new PdoStatement();
            $statement->setPagination($this->pagination);
            $statement->setPrimaryStatement($primary_statement);
            $statement->setCountStatement($count_statement);

            // clear pagination
            $this->pagination = null;

            return $statement;
        } else {
            return parent::prepare($statement, $driver_options);
        }
    }

    /**
     * Take a query and add a limit / offset for pagination purposes
     * @param string $sql The query
     * @param int $page the page number must be > 0
     * @param int $number_per_page the number of results per page, must be > 0
     * @return string the paginated query
     * @throws \LogicException if the page number is < 1
     * @throws \LogicException if the number per page is < 1
     */
    protected function paginateQuery($sql, $page = 1, $number_per_page = 10)
    {
        // TODO: move to paginationstatement?
        if ($page < 1) {
            throw new \LogicException("Page number for paginated results must be at least 1");
        }

        if ($number_per_page < 1) {
            throw new \LogicException("The number of results per page for paginated results must be at least 1");
        }

        try {
            $this->checkForMainOffsetOrLimit($sql);
        } catch (\Exception $e) {
            throw $e;
        }

        $sql .= " LIMIT " . ($number_per_page * ($page - 1)) . "," . $number_per_page;

        return $sql;
    }

    /**
     * Convert the query to a single row count
     * @param string $sql
     * @param return query
     */
    protected function convertToCountQuery($sql)
    {
        try {
            $this->checkForMainOffsetOrLimit($sql);
        } catch (\Exception $e) {
            throw $e;
        }

        return "SELECT
                    COUNT(*) as pagination_count
                " . $this->stripQueryToUnnestedKeyword($sql) . "
                LIMIT
                    1";
    }

    /**
     * Ensure that the query can be paginated
     * @param string $sql the query
     * @return bool
     * @throws \LogicException if either LIMIT or OFFSET is found
     */
    protected function checkForMainOffsetOrLimit($sql)
    {
        if ("" !== $this->stripQueryToUnnestedKeyword($sql, $unnested_keyword = 'LIMIT')) {
            throw new \LogicException("Query cannot contain an unnested LIMIT when trying to paginate");
        }

        if ("" !== $this->stripQueryToUnnestedKeyword($sql, $unnested_keyword = 'OFFSET')) {
            throw new \LogicException("Query cannot contain an unnested OFFSET when trying to paginate");
        }

        return true;
    }

    /**
     * Return substring of the query, starting from the occurance of an un-nested keyword (i.e. FROM which isn't part of a subquery)
     * http://stackoverflow.com/questions/14680132/regex-for-word-not-between-parentheses
     * @param string $query
     * @param string $unnested_query
     * @return string the substring of the query
     */
    protected function stripQueryToUnnestedKeyword($query, $unnested_keyword = 'FROM')
    {
        if (strpos($query, $unnested_keyword) === 0) {
            return $query;
        }

        $before = '';
        while (strpos($query, $unnested_keyword, 1)) {
            $i = strpos($query, $unnested_keyword, 1);
            $before .= substr($query, 0, $i);
            $query = substr($query, $i);

            $count = count_chars($before);

            if ($count[40] == $count[41]) {
                return $query;
            }
        }

        return "";
    }
}
