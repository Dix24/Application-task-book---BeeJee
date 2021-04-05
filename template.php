<!DOCTYPE html>
<html lang="ru">
  <head>
  <meta charset="utf-8" />
  <title><?php echo $page_title; ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
</head>
<body>
  <div class="container">
    <form class="row p-3" action="/" method="post" autocomplete="off">
      <?php
      if(authorization($l,$p) === true){
        echo '<div class="col-12 col-md-11 text-end">
              <input name="quit" type="text" readonly class="form-control-plaintext" value="Вы авторизованы, как: '.$_SESSION["login"].'" autocomplete="off">
              </div>
              <div class="col-12 col-md-1 text-end d-grid">
              <button type="submit" class="btn btn-success btn-md">Выйти</button>
              </div>';
      } else {

        if(authorization($l,$p) === false){
          echo '<div class="alert alert-danger" role="alert">
                <span class="fw-bold">Ошибка входа! Введены некоректные данные.</span>
                </div>';
        }

        echo '<div class="col-12 col-md-5">
              <span class="align-middle fw-bold">Авторизоваться как администратор</span>
              </div>
              <div class="col-12 col-md-3 text-end">
              <input name="login" type="text" class="form-control" placeholder="Логин" value="" autocomplete="off">
              </div>
              <div class="col-12 col-md-3 text-end">
              <input name="password" type="password" class="form-control" placeholder="Пароль" value="" autocomplete="off">
              </div>
              <div class="col-12 col-md-1 text-end d-grid">
              <button type="submit" class="btn btn-success btn-md">Войти</button>
              </div>';
      }
      ?>
    </form>

    <div class="row p-3">
      <div class="col-12">
          <h1 class="text-center"><?php echo $page_title; ?></h1>
      </div>
    </div>

    <?php
    if($insert_id != ''){
      echo '<div class="row p-3">
            <div class="alert alert-danger" role="alert">
            <span class="text-center">Добавлена новая задача под номером: '.$insert_id.'</span>
            </div>
            </div>';
    }
    ?>

    <form class="row" action="/added_message/" method="post">
      <div class="col-12 col-md-6 has-validation">
          <label for="name" class="form-label fs-5 lh-lg">Имя <span class="text-danger">*</span></label>
          <input id="name" name="name" type="text" class="form-control form-control-lg" value="" pattern="[A-Za-zА-Яа-я]{3,30}" required />
          <div class="invalid-feedback">
          Пожалуйста, введите имя пользователя.
          </div>
          <label for="email" class="form-label fs-5 lh-lg">E-mail <span class="text-danger">*</span></label>
          <input id="email" name="email" type="email" class="form-control form-control-lg" value="" pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="name@example.com" required />
          <div class="invalid-feedback">
          Пожалуйста, введите email пользователя.
          </div>
      </div>

      <div class="col-12 col-md-6 has-validation">
          <label for="message" class="form-label fs-5 lh-lg">Задача <span class="text-danger">*</span></label>
          <textarea id="message" name="message" class="form-control form-control-lg" minlength="2" style="height: 143px;" required></textarea>
          <div class="invalid-feedback">
          Пожалуйста, введите задачу.
          </div>
      </div>

      <div class="col-12 text-end d-grid p-3">
          <button type="submit" class="btn btn-danger btn-lg">Создать</button>
      </div>
    </form>
  </div>

  <div class="container">
      <div class="row">
        <div class="col-12">
          <h2 class="text-center p-3">Список задач</h2>

          <form action="/" method="get">
            <div class="btn-group" role="group">
              <div class="input-group">
                <div class="input-group-text" title="Сортировка"><i class="bi bi-filter-left"></i></div>

                <input type="radio" class="btn-check" name="sorting" value="sortingNaAs" id="sortingNaAs" autocomplete="off">
                <label class="btn btn-outline-secondary btn-md" for="sortingNaAs" title="Сортировать по имени">Имя <i class="bi bi-arrow-bar-down"></i></label>
                <input type="radio" class="btn-check" name="sorting" value="sortingNaDe" id="sortingNaDe" autocomplete="off">
                <label class="btn btn-outline-secondary btn-md" for="sortingNaDe" title="Сортировать по имени"><i class="bi bi-arrow-bar-up"></i></label>

                <input type="radio" class="btn-check" name="sorting" value="sortingEmAs" id="sortingEmAs" autocomplete="off">
                <label class="btn btn-outline-secondary btn-md" for="sortingEmAs" title="Сортировать по email">Mail <i class="bi bi-arrow-bar-down"></i></label>
                <input type="radio" class="btn-check" name="sorting" value="sortingEmDe" id="sortingEmDe" autocomplete="off">
                <label class="btn btn-outline-secondary btn-md" for="sortingEmDe" title="Сортировать по email"><i class="bi bi-arrow-bar-up"></i></label>

                <input type="radio" class="btn-check" name="sorting" value="sortingStAs" id="sortingStAs" autocomplete="off">
                <label class="btn btn-outline-secondary btn-md" for="sortingStAs" title="Сортировать по статусу">Статус <i class="bi bi-arrow-bar-down"></i></label>
                <input type="radio" class="btn-check" name="sorting" value="sortingStDe" id="sortingStDe" autocomplete="off">
                <label class="btn btn-outline-secondary btn-md" for="sortingStDe" title="Сортировать по статусу"><i class="bi bi-arrow-bar-up"></i></label>
              </div>
            </div>

            <button type="submit" class="btn btn-warning btn-md">Применить</button>
          </form>
        </div>
      </div>

      <div class="row p-3">
        <?php
        foreach ($message as $key => $value) {
          echo '<div class="col-12 col-sm-6 col-md-4 p-3">
                <p class="text-center text-wrap text-break fs-5 text-primary">'.$value['name'].'</p>
                <p class="text-center fw-bold text-wrap text-break text-secondary">'.$value['email'].'</p>';

          if(authorization($l,$p) === true){
            echo '<form action="/" method="post" class="text-center">';
            echo '<input class="d-none" name="edit_id" type="text" value="'.$value['id'].'" />';
            if($value['status'] == 'Завершенная'){ $checked = ' checked '; } else { $checked = ''; }
            echo '<input id="status-'.$key.'" class="form-check-input" name="edit_status" type="checkbox" value="closed" '.$checked.' />
                  <label for="status-'.$key.'" class="form-check-label">Завершенная</label>';
            echo '<textarea name="edit_message" class="m-3">'.$value['message'].'</textarea>';
            echo '<button type="submit" class="btn btn-warning btn-md">Сохранить</button>';
            echo '</form>';
          } else {
            echo '<p class="text-center text-wrap text-break">';
            if($value['status'] == 'Завершенная'){ echo '<del>'.$value['message'].'</del>'; } else { echo $value['message']; }
            if($value['date_create'] != $value['date_change']){ echo '<br /><small class="text-muted">Задача была отредактирована</small>'; }
            echo '</p>';
          }

          echo '</div>';
        }
        ?>
      </div>

      <div class="row">
        <nav class="col-12">
          <?php
          if($count['col'] > 0){
            echo '<ul class="pagination">';
            for($i=0; $i <= ($count['col']/3); $i++)
            { $x = $i+1; echo '<li class="page-item"><a class="page-link" href="?page='.$i.'">'.$x.'</a></li>'; }
            echo '</ul>';
          }
          ?>
        </nav>
      </div>
  </div>
</body>
</html>
