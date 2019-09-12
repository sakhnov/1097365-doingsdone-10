<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="q" value="<?= $searchWord; ?>" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/?show_tasks=all" class="tasks-switch__item <?= ((!isset($_GET['show_tasks']) || isset($_GET['show_tasks']) && $_GET['show_tasks'] === 'all')) ? 'tasks-switch__item--active' : ''; ?>">Все задачи</a>
            <a href="/?show_tasks=today" class="tasks-switch__item <?= (isset($_GET['show_tasks']) && $_GET['show_tasks'] === 'today') ? 'tasks-switch__item--active' : ''; ?>">Повестка дня</a>
            <a href="/?show_tasks=tomorrow" class="tasks-switch__item <?= (isset($_GET['show_tasks']) && $_GET['show_tasks'] === 'tomorrow') ? 'tasks-switch__item--active' : ''; ?>">Завтра</a>
            <a href="/?show_tasks=overdue" class="tasks-switch__item <?= (isset($_GET['show_tasks']) && $_GET['show_tasks'] === 'overdue') ? 'tasks-switch__item--active' : ''; ?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= ($show_complete_tasks == 1) ? 'checked' : ''; ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">

        <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->

        <?php foreach ($tasks as $value): ?>
            <?php if ($value['status'] == true && $show_complete_tasks == 0) {
                continue;
            } ?>

            <tr class="tasks__item task <?= ($value['status'] == true) ? 'task--completed' : ''; ?> <?= isDeadlineClose(htmlspecialchars($value['deadline'])) ? 'task--important' : ''; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox" >
                        <span class="checkbox__text"><?= htmlspecialchars($value['title']); ?> <?= ($value['file']) ? '(<a href="'.$value['file'].'">file</a>)' : ''; ?> </span>
                    </label>
                </td>
                <td class="task__date"><?= htmlspecialchars($value['deadline']); ?></td>
                <td class="task__controls">
                    <button class="expand-control" type="button" name="button">Дополнительные действия</button>
                    <ul class="expand-list hidden">
                        <li class="expand-list__item">
                            <?php if($value['status']): ?>
                                <a href="?complete_task=0&task_id=<?=$value['id']?>">
                                    Отметить как невыполненную
                                </a>
                            <?php else: ?>
                                <a href="?complete_task=1&task_id=<?=$value['id']?>">
                                    Выполнить
                                </a>
                            <?php endif; ?>
                        </li>
                        <li class="expand-list__item">
                            <a href="?delete_task=<?=$value['id']?>">
                                Удалить
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>
    <?= ($tasks) ? '' : 'Ничего не найдено по вашему запросу!'; ?>
</main>
