<?php
class DB
{
    private $connection; // свойство boolean, м. использовать только внутри класса
    public $last_error = ''; //2 свойства класса

    public function __construct() {//создаёт подключение к БД
        $this->connection = new mysqli("localhost","root","","comments");// записываем в переменную объект
 //здесь connection стал объектом
        if ($this->connection->connect_error) {//проверка, было ли подключение
            $this->last_error = "Failed to connect to MySQL: " . $this->connection->connect_error;
            $this->connection = false;
   
        }
    }
    public function __deconstruct(){
        if (!$this->connection){
            echo $this->last_error;
            return;
        }
        $this->connection->close();
    }


    public function displayAll() {//выведется когда создан объект
        if (!$this->connection){//проверка,если невалидное подключение, выведет ошибку
            echo $this->last_error;
            return;
        }
        $result = $this->connection->query("SELECT * FROM comments");//result- объект, не массив+ если удалось подключиться
        //звёздочка указывает на всё из comments, м. указать опред. колонки, не всю таблицу
        if ($result->num_rows > 0) {//проверка сколько строк в результате
            echo "<ul class='entry-list'>";
            while($row = $result->fetch_assoc()) {//выполняется, пока правдиво строка=проверка, результат  метода,
                /* повторно метод вызывая - новая запись, row - содержит массив с данными
                fetch assoc Возвращает ассоциативный массив строк, соответствующий результирующей 
            выборке, где каждый ключ в массиве соответствует имени одного из столбцов
             выборки или null , если других рядов не существует.*/
                echo '<li><span>' . $this->text($row['name']) . '</span>' . $this->text($row['comment']) . '<a href="update.php?update=' . $this->text($row['id']) . '>Update</a></li>';// выводим список
            }
            echo "</ul>";
        } else {
            echo "0 results";
        }
    }

    private function text($value){
        return htmlentities($value, ENT_COMPAT | ENT_HTML401 | ENT_QUOTES, 'utf-8');
    }

    public function add($name, $comment){
        if (!$this->connection){
            return $this->last_error;
            //прерываение ф-и (может и файла)
        }

        $sql=sprintf(//команда связана с БД, составляем текст
            "INSERT INTO comments (`name`, `comment`) VALUES ('%s', '%s')",
            $this->connection->escape_string($name), //кавычка- часть текста
            $this->connection->escape_string($comment)//обрабатывает текст        
        );
        echo 'sql:' . $sql;
        //в какую таблицу - comment,какие значения вставляем
        $result = $this->connection->query($sql);
        if ($result != true){//если неуспешно - выводим ошибку
            return 'Insert failed:' . $this->connection->error;
        }  
           return 'Inserted';
    }

    //fetch_assoc()['name'] - м. вызвать ключ]

    public function update($id, $name, $comment) {
        if (!$this->connection) {
            return $this->last_error;
        }

        $sql = sprintf(
            "UPDATE comments SET `name`='%s', `comment`='%s' WHERE id=%s",
            $this->connection->escape_string($name),
            $this->connection->escape_string($comment),
            $this->connection->escape_string($id)
        );
        
        $result = $this->connection->query($sql);
        if ($result != true) {
            return 'Update failed: ' . $this->connection->error;
        }
        
        return 'Updated';
    }
}