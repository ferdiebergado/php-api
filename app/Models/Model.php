<?php
namespace App\Models;

use App\Models\ModelInterface;
use ParagonIE\EasyDB\EasyDB;

class Model implements ModelInterface
{
    protected static $db;
    protected static $table;
    protected $fillable;
    protected static $guarded;

    public function __construct(EasyDB $db)
    {
        self::$db = $db;
        if (empty(self::$table)) {
            self::$table = strtolower(str_replace('App\\Models\\', '', (\get_class(self)))) . 's';
        }
    }

    public static function find($id)
    {
        return self::guard(self::$db->row("SELECT * FROM " . self::$table . " WHERE id = ?", [$id]));
    }

    public function update($id, array $fields)
    {
        return $this->db->update($this->table, $fields, ['id' => $id]);
    }

    public static function guard($array)
    {
        foreach (self::$guarded as $guard) {
            unset($array[$guard]);
        }
        return $array;
    }
}
