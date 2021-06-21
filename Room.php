<?php
abstract class Room
{
    protected $left = null, $right = null, $up = null, $down = null, $visited, $rate, $exit;
    public abstract function action();

    public function __construct($rate = 0, $exit = false)
    {
        $this->rate = $rate;
        $this->exit = boolval($exit);
    }

    /**
     * @return Room
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param Room $left
     */
    public function setLeft($left)
    {
        $this->left = $left;
    }

    /**
     * @return Room
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param Room $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * @return Room
     */
    public function getUp()
    {
        return $this->up;
    }

    /**
     * @param Room $up
     */
    public function setUp($up)
    {
        $this->up = $up;
    }

    /**
     * @return Room
     */
    public function getDown()
    {
        return $this->down;
    }

    /**
     * @param Room $down
     */
    public function setDown($down)
    {
        $this->down = $down;
    }
}

class EmptyRoom extends Room {
    public function __construct($exit)
    {
        parent::__construct(0, boolval($exit));
    }

    public function action()
    {
        return 0;
    }
}

class BounteRoom extends Room {
    public function action()
    {
        if (!$this->visited) {
            $this->visited = true;
            return rand(($this->rate - 1) * 6, $this->rate * 6);
        }
        else return 0;
    }
}

    class MonsterRoom extends Room {
    private $power, $cur, $step;
    public function __construct($rate = 1, $exit = false)
    {
        parent::__construct($rate, boolval($exit));
        $this->power = rand(($this->rate - 1) * 3, $this->rate * 3);
        $this->cur = $this->power;
        $this->step = 6 - $rate;
    }

    public function action()
    {
        if (!$this->visited) {
            if (rand(($this->rate - 1) * 4, $this->rate * 4) >= $this->cur) {
                $this->visited = true;
                return $this->power;
            } else
                $this->cur -= $this->step;
        }
        return 0;
    }

    public function getLeft()
    {
        if ($this->visited) return null;
        return parent::getLeft();
    }

    public function getRight()
    {
        if ($this->visited) return null;
        return parent::getRight();
    }

    public function getUp()
    {
        if ($this->visited) return null;
        return parent::getUp();
    }

    public function getDown()
    {
        if ($this->visited) return null;
        return parent::getDown();
    }


}