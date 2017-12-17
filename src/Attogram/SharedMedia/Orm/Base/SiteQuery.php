<?php

namespace Attogram\SharedMedia\Orm\Base;

use \Exception;
use \PDO;
use Attogram\SharedMedia\Orm\Site as ChildSite;
use Attogram\SharedMedia\Orm\SiteQuery as ChildSiteQuery;
use Attogram\SharedMedia\Orm\Map\SiteTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'site' table.
 *
 *
 *
 * @method     ChildSiteQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSiteQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildSiteQuery orderByAbout($order = Criteria::ASC) Order by the about column
 * @method     ChildSiteQuery orderByHeader($order = Criteria::ASC) Order by the header column
 * @method     ChildSiteQuery orderByFooter($order = Criteria::ASC) Order by the footer column
 * @method     ChildSiteQuery orderByUseCdn($order = Criteria::ASC) Order by the use_cdn column
 * @method     ChildSiteQuery orderByCuration($order = Criteria::ASC) Order by the curation column
 * @method     ChildSiteQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildSiteQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildSiteQuery groupById() Group by the id column
 * @method     ChildSiteQuery groupByTitle() Group by the title column
 * @method     ChildSiteQuery groupByAbout() Group by the about column
 * @method     ChildSiteQuery groupByHeader() Group by the header column
 * @method     ChildSiteQuery groupByFooter() Group by the footer column
 * @method     ChildSiteQuery groupByUseCdn() Group by the use_cdn column
 * @method     ChildSiteQuery groupByCuration() Group by the curation column
 * @method     ChildSiteQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildSiteQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildSiteQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSiteQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSiteQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSiteQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSiteQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSiteQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSite findOne(ConnectionInterface $con = null) Return the first ChildSite matching the query
 * @method     ChildSite findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSite matching the query, or a new ChildSite object populated from the query conditions when no match is found
 *
 * @method     ChildSite findOneById(int $id) Return the first ChildSite filtered by the id column
 * @method     ChildSite findOneByTitle(string $title) Return the first ChildSite filtered by the title column
 * @method     ChildSite findOneByAbout(string $about) Return the first ChildSite filtered by the about column
 * @method     ChildSite findOneByHeader(string $header) Return the first ChildSite filtered by the header column
 * @method     ChildSite findOneByFooter(string $footer) Return the first ChildSite filtered by the footer column
 * @method     ChildSite findOneByUseCdn(boolean $use_cdn) Return the first ChildSite filtered by the use_cdn column
 * @method     ChildSite findOneByCuration(boolean $curation) Return the first ChildSite filtered by the curation column
 * @method     ChildSite findOneByCreatedAt(string $created_at) Return the first ChildSite filtered by the created_at column
 * @method     ChildSite findOneByUpdatedAt(string $updated_at) Return the first ChildSite filtered by the updated_at column *

 * @method     ChildSite requirePk($key, ConnectionInterface $con = null) Return the ChildSite by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOne(ConnectionInterface $con = null) Return the first ChildSite matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSite requireOneById(int $id) Return the first ChildSite filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByTitle(string $title) Return the first ChildSite filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByAbout(string $about) Return the first ChildSite filtered by the about column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByHeader(string $header) Return the first ChildSite filtered by the header column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByFooter(string $footer) Return the first ChildSite filtered by the footer column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByUseCdn(boolean $use_cdn) Return the first ChildSite filtered by the use_cdn column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByCuration(boolean $curation) Return the first ChildSite filtered by the curation column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByCreatedAt(string $created_at) Return the first ChildSite filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByUpdatedAt(string $updated_at) Return the first ChildSite filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSite[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSite objects based on current ModelCriteria
 * @method     ChildSite[]|ObjectCollection findById(int $id) Return ChildSite objects filtered by the id column
 * @method     ChildSite[]|ObjectCollection findByTitle(string $title) Return ChildSite objects filtered by the title column
 * @method     ChildSite[]|ObjectCollection findByAbout(string $about) Return ChildSite objects filtered by the about column
 * @method     ChildSite[]|ObjectCollection findByHeader(string $header) Return ChildSite objects filtered by the header column
 * @method     ChildSite[]|ObjectCollection findByFooter(string $footer) Return ChildSite objects filtered by the footer column
 * @method     ChildSite[]|ObjectCollection findByUseCdn(boolean $use_cdn) Return ChildSite objects filtered by the use_cdn column
 * @method     ChildSite[]|ObjectCollection findByCuration(boolean $curation) Return ChildSite objects filtered by the curation column
 * @method     ChildSite[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildSite objects filtered by the created_at column
 * @method     ChildSite[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildSite objects filtered by the updated_at column
 * @method     ChildSite[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SiteQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Attogram\SharedMedia\Orm\Base\SiteQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Attogram\\SharedMedia\\Orm\\Site', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSiteQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSiteQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSiteQuery) {
            return $criteria;
        }
        $query = new ChildSiteQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSite|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SiteTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SiteTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSite A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, title, about, header, footer, use_cdn, curation, created_at, updated_at FROM site WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildSite $obj */
            $obj = new ChildSite();
            $obj->hydrate($row);
            SiteTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSite|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SiteTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SiteTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the about column
     *
     * Example usage:
     * <code>
     * $query->filterByAbout('fooValue');   // WHERE about = 'fooValue'
     * $query->filterByAbout('%fooValue%', Criteria::LIKE); // WHERE about LIKE '%fooValue%'
     * </code>
     *
     * @param     string $about The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByAbout($about = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($about)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_ABOUT, $about, $comparison);
    }

    /**
     * Filter the query on the header column
     *
     * Example usage:
     * <code>
     * $query->filterByHeader('fooValue');   // WHERE header = 'fooValue'
     * $query->filterByHeader('%fooValue%', Criteria::LIKE); // WHERE header LIKE '%fooValue%'
     * </code>
     *
     * @param     string $header The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByHeader($header = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($header)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_HEADER, $header, $comparison);
    }

    /**
     * Filter the query on the footer column
     *
     * Example usage:
     * <code>
     * $query->filterByFooter('fooValue');   // WHERE footer = 'fooValue'
     * $query->filterByFooter('%fooValue%', Criteria::LIKE); // WHERE footer LIKE '%fooValue%'
     * </code>
     *
     * @param     string $footer The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByFooter($footer = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($footer)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_FOOTER, $footer, $comparison);
    }

    /**
     * Filter the query on the use_cdn column
     *
     * Example usage:
     * <code>
     * $query->filterByUseCdn(true); // WHERE use_cdn = true
     * $query->filterByUseCdn('yes'); // WHERE use_cdn = true
     * </code>
     *
     * @param     boolean|string $useCdn The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByUseCdn($useCdn = null, $comparison = null)
    {
        if (is_string($useCdn)) {
            $useCdn = in_array(strtolower($useCdn), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_USE_CDN, $useCdn, $comparison);
    }

    /**
     * Filter the query on the curation column
     *
     * Example usage:
     * <code>
     * $query->filterByCuration(true); // WHERE curation = true
     * $query->filterByCuration('yes'); // WHERE curation = true
     * </code>
     *
     * @param     boolean|string $curation The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByCuration($curation = null, $comparison = null)
    {
        if (is_string($curation)) {
            $curation = in_array(strtolower($curation), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_CURATION, $curation, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSite $site Object to remove from the list of results
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function prune($site = null)
    {
        if ($site) {
            $this->addUsingAlias(SiteTableMap::COL_ID, $site->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the site table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SiteTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SiteTableMap::clearInstancePool();
            SiteTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SiteTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SiteTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SiteTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SiteTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SiteTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SiteTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SiteTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SiteTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SiteTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SiteTableMap::COL_CREATED_AT);
    }

} // SiteQuery
