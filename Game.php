<?php
require_once 'Room.php';
class Game {
    /**
     * @var $rooms Room[]
     * @var $cur Room
     */
    private $rooms, $cur;
    private $score = 0;
    /**
     * Game constructor.
     * @param $map - Карта подземелья. Json объект с двумя полями: rooms и paths.
     * rooms - массив объектов с полями id, type: (0 - пустая, 1 - сундук, 2 - монстр), rate и exit.
     * paths - объект с полями id комнаты содержащими массивы id комнат или null
     * в которые есть проходы по порядку (up, down, left, right)
     */
    public function __construct($map)
    {
        $tmp = json_decode($map, true);
        foreach ($tmp['rooms'] as $room) {
            switch ($room['type']) {
                case 0:
                    $this->rooms[$room['id']] = new EmptyRoom($room['exit']);
                    break;
                case 1:
                    $this->rooms[$room['id']] = new BounteRoom($room['rate'], $room['exit']);
                    break;
                case 2:
                    $this->rooms[$room['id']] = new MonsterRoom($room['rate'], $room['exit']);
                    break;
                default: break;
            }
        }
        foreach ($tmp['paths'] as $id => $path) {
            $i = 0;
            if (!is_null($path[$i])) $this->rooms[intval($id)]->setUp($this->rooms[$path[$i]]);
            if (!is_null($path[++$i])) $this->rooms[intval($id)]->setDown($this->rooms[$path[$i]]);
            if (!is_null($path[++$i])) $this->rooms[intval($id)]->setLeft($this->rooms[$path[$i]]);
            if (!is_null($path[++$i])) $this->rooms[intval($id)]->setRight($this->rooms[$path[$i]]);
        }
    }

    public function setStartRoom($rid) {
        $this->cur = $this->rooms[$rid];
    }
    //0 - up, 1 - down, 2 - left, 3 - right
    public function movePlayer($direction) {
        if ($this->cur->exit) return "finish";
        $tmp = $this->cur;
        switch ($direction) {
            case 0: if (!is_null($this->cur->getUp())) $this->cur = $this->cur->getUp(); break;
            case 1: if (!is_null($this->cur->getDown())) $this->cur = $this->cur->getDown(); break;
            case 2: if (!is_null($this->cur->getLeft())) $this->cur = $this->cur->getLeft(); break;
            case 3: if (!is_null($this->cur->getRight())) $this->cur = $this->cur->getRight(); break;
        }
        if ($tmp != $this->cur) return gettype($this->cur);
        if ($this->cur->exit) return "finish";
        return null;
    }

    public function actionPlayer() {
        $tmp = $this->cur->action();
        $this->score += $tmp;
        return $tmp;
    }

    public function getScore() {
        return $this->score;
    }
}

