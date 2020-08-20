<?php


namespace App\Services\Groups;


use App\Helpers\Str;

class Group
{
    /**
     * Админский ранг.
     */
    public const ADMIN_RANK = 999;

    /**
     * Навание группы.
     *
     * @var string
     */
    protected $name;

    /**
     * Отображаемое навание группы.
     *
     * @var string
     */
    protected $screenName;

    /**
     * Ранг группы.
     * Чем выше - тем лучше группа.
     *
     * @var int
     */
    protected $rank;

    /**
     * Цена группы.
     * 0 - если не продается.
     *
     * @var int
     */
    protected $price;

    /**
     * Является ли группой по-умолчанию.
     *
     * @var bool
     */
    protected $isDefault = false;

    /**
     * Является ли группа админской.
     *
     * @var bool
     */
    protected $isAdmin = false;

    /**
     * Group constructor.
     *
     * @var array $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->screenName = $data['screen_name'] ?? Str::ucfirst($data['name']);
        $this->rank = $data['rank'] ?? 0;
        $this->price = $data['price'] ?? 0;
        $this->isDefault = $data['is_default'] ?? false;
        $this->isAdmin = $data['is_admin'] ?? false;
    }

    /**
     * Получить название группы.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Получить отображаемое название группы.
     *
     * @return string
     */
    public function getScreenName(): string
    {
        return $this->screenName;
    }

    /**
     * Получить ранг группы.
     *
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * Получить цену группы.
     *
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Проверить, является ли группа продаваемой?
     *
     * @return bool
     */
    public function isSelling(): bool
    {
        return $this->price > 0;
    }

    /**
     * Проверить, является ли группа группой по-умолчанию.
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * Проверить, является ли группа админской.
     *
     * @return bool
     */
    public function isStaff(): bool
    {
        return $this->isAdmin;
    }

    /**
     * Создать пустую группу.
     * Если группа не будет найдена в конфиге, но она будет у игрока,
     * то создаем объект этой группы-пустышки.
     *
     * @param string $name
     * @return static
     */
    public static function createEmptyGroup(string $name): self
    {
        return new self([
            'name' => $name,
            'rank' => 0
        ]);
    }
}
