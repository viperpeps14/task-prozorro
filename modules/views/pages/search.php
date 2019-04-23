<h1>Знайдено: <span><?= $tenders['count_result'] ?></span></h1>
<div class="row">
    <div class="filter col-md-3">
        <form method="GET" action="/pages/search/">
            <div class="form-group">
               <label for="exampleFormControlInput1">Назва (title)</label>
               <input type="text" name="title" class="form-control" id="exampleFormControlInput1">
            </div>
            <div class="form-group">
               <label for="exampleFormControlInput2">Код ЕДРПОУ</label>
               <input type="text" name="EDRPOU" class="form-control" id="exampleFormControlInput2">
            </div>
            <div class="form-group">
               <label for="exampleFormControlInput3">Классификатор</label>
               <input type="text" name="classifier" class="form-control" id="exampleFormControlInput3">
            </div>
            <button type="submit" class="btn btn-primary">Пошук</button>
         </form>
    </div>
  
    <div class="container-tenders col-md-9">
        <?php
        foreach($tenders['result'] as $item): 
            if($status[$item['status']]){
                $status_tender = $status[$item['status']]; 
            }else{
               $status_tender = "Статус не визначено"; 
            }  
        ?>
        <div class="item_tender row">
            <div class="position_left col-md-9">
                <div class="title_tender"><a href="/pages/tender/<?=$item['id']?>/"><?=$item['title']?></a></div>
                <div class="elek_tender">Електронні закупівлі <span class="status"><?=$status_tender?></span>
                    <span class="city_tender"><?=$item['locality']?></span></div>
                <div class="company_tender"><span>Компанія: </span><?=$item['name']?></div>
                <div class="id_tender"><span>ID: </span><?=$item['tenderID']?></div>
            </div>
            <div class="position_right col-md-3">
                <div class="title_price">очікувана вартість</div>
                <div class="price"><?=number_format($item['amount'], 2, ',', ' ')?> <span><?=$item['currency']?></span></div>
                <div class="stunned">Оголошено: <span><?php echo date("d-m-Y", $item['date']) ?></span></div>
            </div>
            <hr>
        </div>
        <?php endforeach; ?>
    </div>
</div>