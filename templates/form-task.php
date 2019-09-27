            <main class="content__main">
                <h2 class="content__main-heading">Добавление задачи</h2>

                <form class="form"  action="" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="form__row">
                        <label class="form__label" for="name">Название <sup>*</sup></label>

                        <input class="form__input <?= (isset($errors['name'])) ? 'form__input--error' : '' ?>"
                               type="text" name="name" id="name" value="<?= getPostVal('name'); ?>" placeholder="Введите название">
                        <p class="form__message"><?= $errors['name'] ?? ""; ?></p>
                    </div>

                    <div class="form__row">
                        <label class="form__label" for="project">Проект <sup>*</sup></label>

                        <select class="form__input form__input--select <?= (isset($errors['project'])) ? 'form__input--error' : '' ?>" name="project" id="project">
                            <option >-- Выберите проект --</option>
                            <?php foreach ($main_list as $value): ?>
                                <option value="<?= $value['id'] ?? ""; ?>" <?= (isset($value['id']) && $value['id'] == getPostVal('project')) ? 'selected' : '' ?> ><?= htmlspecialchars($value['project_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="form__message"><?= $errors['project'] ?? ""; ?></p>
                    </div>

                    <div class="form__row">
                        <label class="form__label" for="date">Дата выполнения</label>

                        <input class="form__input form__input--date <?= (isset($errors['date'])) ? 'form__input--error' : '' ?>" type="text" name="date" id="date" value="<?= getPostVal('date'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                        <p class="form__message"><?= $errors['date'] ?? ""; ?></p>
                    </div>

                    <div class="form__row">
                        <label class="form__label" for="file">Файл</label>

                        <div class="form__input-file">
                            <input class="visually-hidden" type="file" name="file" id="file" >

                            <label class="button button--transparent" for="file">
                                <span>Выберите файл</span>
                            </label>
                            <p class="form__message"><?= $errors['file'] ?? ""; ?></p>
                        </div>
                    </div>

                    <div class="form__row form__row--controls">
                        <input class="button" type="submit" name="" value="Добавить">
                    </div>
                </form>
            </main>
