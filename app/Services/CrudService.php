<?php

namespace App\Services;

use Log;
use Exception;
use Illuminate\Support\Facades\Schema;
use DB;

class CrudService
{
    public static function index($tableName, $column = [], $pagination = 10,$orderBy='desc')
    {
        try {
            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);
            if(!$tableName) throw new Exception('Please provide table name or id');
            self::checkIfTableExist($tableName);
            if ($column) {
                return DB::table($tableName)->select($column)->orderBy('id',$orderBy)->paginate($pagination);
            } else {
                return DB::table($tableName)->orderBy('id',$orderBy)->paginate($pagination);
            }
        } catch (Exception $e) {
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            throw new Exception($e->getMessage());
        }
    }

    public static function details($tableName, $id, $column = [])
    {
        try {
            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);
            if(!$tableName || !$id) throw new Exception('Please provide relevant table name');
            self::checkIfTableExist($tableName);
            if ($column) {
                return DB::table($tableName)->select($column)->whereId($id)->first();
            } else {
                return DB::table($tableName)->select($column)->whereId($id)->first();
            }
        } catch (Exception $e) {
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            throw new Exception($e->getMessage());
        }
    }

    public static function update($tableName, $id, array $data = [])
    {
        try {

            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);
            if(!$tableName || !$id) throw new Exception('Please provide table name or id');
            self::checkIfTableExist($tableName);
            $data = DB::table($tableName)->whereId($id)->update($data);
            if(!$data) throw new Exception('Unable to update data');
            return $data;
        } catch (Exception $e) {
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            throw new Exception($e->getMessage());
        }
    }

    public static function create($tableName,array $data = [])
    {
        try {

            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);
            if(!$tableName || !$data) throw new Exception('Please provide table name or data');
            self::checkIfTableExist($tableName);
            $data = DB::table($tableName)->insert($data);
            if(!$data) throw new Exception('Unable to create data');
            return $data;
        } catch (Exception $e) {
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            throw new Exception($e->getMessage());
        }
    }


    public static function delete($tableName,$id)
    {
        try {

            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);
            if(!$tableName || !$id) throw new Exception('Please provide table name or id');
            self::checkIfTableExist($tableName);
            $data = DB::table($tableName)->whereId($id)->delete();
            if(!$data) throw new Exception('Unable to delete data');
            return $data;
        } catch (Exception $e) {
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            throw new Exception($e->getMessage());
        }
    }



    public static function checkIfTableExist($tableName)
    {
        try {
            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);
            $exists = Schema::hasTable($tableName);
            if (!$exists) {
                throw new Exception('No table exist');
            }
            return $exists;
        } catch (Exception $e) {
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            throw new Exception($e->getMessage());
        }
    }
}
