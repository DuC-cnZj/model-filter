<?php

namespace DucCnzj\ModelFilter\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use DucCnzj\ModelFilter\Traits\HasFilter;

class TestModel extends Model
{
    use HasFilter;
}
