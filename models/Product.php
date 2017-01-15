<?php

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 11.01.17
 * Time: 22:40
 */
class Product
{

    const SHOW_BY_DEFAULT=6;

   public static function getLatestProducts($count=self::SHOW_BY_DEFAULT) {
       $count = intval($count);
       $db = Db::getConnection();
       $productsList=[];

       // Текст запроса к БД
       $sql = 'SELECT id, name, price, image,is_new FROM product '
           . 'WHERE status = "1" ORDER BY id DESC '
           . 'LIMIT :count';
       // Используется подготовленный запрос
       $result = $db->prepare($sql);
       $result->bindParam(':count', $count, PDO::PARAM_INT);
       // Указываем, что хотим получить данные в виде массива
       $result->setFetchMode(PDO::FETCH_ASSOC);

       // Выполнение коменды
       $result->execute();

       $i=0;
       while ($row=$result->fetch()) {
           $productsList[$i]['id']=$row['id'];
           $productsList[$i]['name']=$row['name'];
           $productsList[$i]['price']=$row['price'];
           $productsList[$i]['image']=$row['image'];
           $productsList[$i]['is_new']=$row['is_new'];
           $i++;
       }


           return $productsList;

   }

    public static function getProductsListByCategory($categoryId=false,$page=1) {
        if ($categoryId) {
            $limit=Product::SHOW_BY_DEFAULT;
            $page=intval($page);
            $offset=($page-1) * self::SHOW_BY_DEFAULT;
            var_dump($offset);
            $db=Db::getConnection();
            $products=[];

            $sql='SELECT id, name, price, image, is_new FROM product '
                . 'WHERE status = 1 AND category_id = :category_id '
                . 'ORDER BY id ASC LIMIT :limit OFFSET :offset';
            

            // Используется подготовленный запрос
            $result = $db->prepare($sql);
            $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            $result->bindParam(':limit', $limit, PDO::PARAM_INT);
            $result->bindParam(':offset', $offset, PDO::PARAM_INT);
            // Выполнение коменды
            $result->execute();
            var_dump($result);

            $i=0;
            while ($row=$result->fetch()) {
                $products[$i]['id']=$row['id'];
                $products[$i]['name']=$row['name'];
                $products[$i]['price']=$row['price'];
                $products[$i]['image']=$row['image'];
                $products[$i]['is_new']=$row['is_new'];
                $i++;
            }
            return $products;
        }
    }

    public static function getProductById($id) {
        $id=intval($id);

        if ($id) {
            $db=Db::getConnection();
            $result=$db->query('SELECT * FROM product WHERE id='. $id);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            return $result->fetch();
        }
    }
}