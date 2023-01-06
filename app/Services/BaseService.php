<?php


namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class BaseService
{
    protected $capsule;
    protected $table;

    public function __construct()
    {
        $this->capsule = new Capsule;
    }

    public function get()
    {
        return $this->table()->get();
    }

    public function insert(array $data)
    {
        return $this->table()
            ->insertGetId($data);
    }

    public function getCurrentDate()
    {
        return date('Y-m-d H:i:s', time());
    }

    public function table()
    {
        return $this->capsule->table($this->table);
    }

    public function countBy($attribute, $value)
    {
        return $this->table()->where($attribute, $value)->count();
    }

    public function updateBy($attribute, $value, array $data)
    {
        return $this->table()
            ->where($attribute, $value)
            ->update($data);
    }

    public function updateByAttributes(array $wheres, $data) {
        $query = $this->table();
        foreach($wheres as $attribute => $value) {
            $query->where($attribute, $value);
        }

        return $query->update($data);
    }

    public function getBy($attribute, $value)
    {
        return $this->table()
            ->where($attribute, $value)
            ->get();
    }

    public function getFirstBy($attribute, $value)
    {
        return $this->table()
            ->where($attribute, $value)
            ->first();
    }

    public function getFirstByAttributres($wheres)
    {
        $query = $this->table();
        foreach($wheres as $attribute => $value) {
            $query->where($attribute, $value);
        }

        return $query->first();
    }

    public function exists($attribute, $value)
    {
        return $this->table()
            ->where($attribute, $value)
            ->exists();
    }

    public function existsBy($where)
    {
        $query = $this->table();
        foreach ($where as $attribute => $value) {
            $query->where($attribute, $value);
        }

        return $query->exists();
    }

    public function pluck($column1, $column2, $filters = [])
    {
        $query = $this->table()
            ->orderBy('ID', 'DESC');
        if($filters) {
            foreach($filters as $attribute => $filter) {
                $query->where($attribute, $filter);
            }
        }
        return $query->pluck($column1, $column2);
    }

    public function getByCompanyId($companyId)
    {
        return $this->table()
            ->where('company_id', $companyId)
            ->orderByDesc('ID')
            ->pluck('department', 'id');
    }
}
