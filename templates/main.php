<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($main_list as $value): ?>
                <li class="main-navigation__list-item <?= ($_GET["project"] == $value['id']) ? 'main-navigation__list-item--active' : ''; ?>">
                    <a class="main-navigation__list-item-link" href="/?project=<?= $value['id']; ?>"><?= htmlspecialchars($value['project_name']); ?></a>
                    <span class="main-navigation__list-item-count"><?= htmlspecialchars($value['count_task']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
       href="/pages/form-project.html" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
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
                        <span class="checkbox__text"><?= htmlspecialchars($value['title']); ?> <?= ($value['file']) ? '(<a href="'.$value['file'].'">file</a>)' : ''; ?></span>
                    </label>
                </td>
                <td class="task__date"><?= htmlspecialchars($value['deadline']); ?></td>
                <td class="task__controls"></td>
            </tr>

        <?php endforeach; ?>
    </table>
</main>
