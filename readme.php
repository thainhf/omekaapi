<!DOCTYPE html>
<html lang="th">
   <HEAD>  
      <meta charset="UTF-8">
   <title> <!-- This tag is used to display the title of the Web Page -->  

   </title>  
 

   </HEAD>  
   <BODY>  
    <?php
    $url_ser="http://ec2-52-221-226-228.ap-southeast-1.compute.amazonaws.com/omekaapi/";
    $url_ser="http://localhost/omekaapi/";

    $root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
    $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $config['base_url'] = $root;
    echo $config['base_url']."<br><br>";
    ?>

      #api คลังข้อมูลดิจิทัล
      <br>
      เรียก ผ่าน localhost   <br>
      http://localhost/omekaapi/api   <br>
      https://omeka.p-set.org/api/
      <br>
      <?php
      echo $config['base_url'];
      ?>
      
      <br>

      <br>
      <p>1. เนื้อหาคัดสรร (ปี crty = 2023 , เดือน เมษายน crtm=04, จำนวนรายการต่อหน้า limitpermonth=10) [crty=yyyy,crtm=mm,limitpermonth=n]",<br>
      ตัวอย่าง url: "http://localhost/omekaapi/api/monthlylist?crty=2023&crtm=04&limitpermonth=10"<br>
      <br>
     
      <a href="<?php echo $config['base_url']."monthlylist.php?crty=2023&crtm=04&limitpermonth=10"; ?>" target="_blank"><?php echo $config['base_url']."flashbacklist.php?skipy=5&limitperpage=10"; ?>monthlylist.php?crty=2023&crtm=04&limitpermonth=10</a>
      </p>
         <br>
      2. เนื้อหายอดนิยม 5 รายการ hits สูงสุด toplimit [n]
      ตัวอย่าง url: "http://localhost/omekaapi/api/popularlist?toplimit=10"

    <br> <br>
    <a target="_blank" href="<?php echo $config['base_url']."popularlist.php?toplimit=10"; ?>"><?php echo $config['base_url']."flashbacklist.php?skipy=5&limitperpage=10"; ?>popularlist.php?toplimit=10</a>

         <br>
      3. หารายการ ของปี ย้อนสำรวจความทรงจำสำคัญ ที่เคยเกิดขึ้น ในเดือน เมษายน (เมื่อ 20 ปีที่แล้ว) skipy=20, เดือน skipm=00 ทุกเดือน/ limitperpage=10 ต่อหน้า [skipy=20, skipm=04, limitperpage=10]",
      ตัวอย่าง url: "http://localhost/omekaapi/api/flashbacklist?skipy=20&skipm=04&limitperpage=10"
    <br>

    <br>
    <a target="_blank" href="<?php echo $config['base_url']."flashbacklist.php?skipy=5&limitperpage=10"; ?>">
        <?php echo $config['base_url']."flashbacklist.php?skipy=5&limitperpage=10"; ?>flashbacklist.php?skipy=5&limitperpage=10
    </a>
      <br> <br>
      4. ชวนออกสำรวจ ตามหัวเรื่อง เลือกสุ่มจำนวนหัวเรื่อง/ limit=10 จำนวนรายการ [limit=10]
      ตัวอย่าง url: "http://localhost/omekaapi/api/letsgolist?limit=10"
      <br>
    <a target="_blank" href="<?php echo $config['base_url']."letsgolist.php?limit=10"; ?>">
        <?php echo $config['base_url']."letsgolist.php?limit=10"; ?>
    </a>
    <br> <br>

      5. เนื้อหารายละเอียด รายการ / id=2114  [id=2114]
      ตัวอย่าง url: "http://localhost/omekaapi/api/item?id=2114"
      <br>4532
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."item.php?id=2114"; ?>">
        <?php echo $config['base_url']."item.php?id=2114"; ?>
    </a>
    <br> <br>

      6. คลังเอกสารอิเล็กทรอนิกส์ที่เกี่ยวข้อง / id=1966  [id=1966,limit=8]
      ตัวอย่าง url: "http://localhost/omekaapi/api/relations.php?id=1966&limit=8"
      <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."relations.php?id=1966&limit=8"; ?>">
        <?php echo $config['base_url']."relations.php?id=1966&limit=8"; ?>
    </a>
    <br> <br>
      7. คลังเอกสารอิเล็กทรอนิกส์ / page=หน้าที่ , limitperpage=จำนวนรายการต่อหน้า  [limitperpage=10]
      ตัวอย่าง url: "http://localhost/omekaapi/api/archivelist?page=1&limitperpage=10"
    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."archivelist.php?page=1&limitperpage=10"; ?>">
        <?php echo $config['base_url']."archivelist.php?page=1&limitperpage=10"; ?>
    </a>
    <br> <br>
      <br>
      8. หมวดหมู่ คลังเอกสารอิเล็กทรอนิกส์ 
      ตัวอย่าง url: "http://localhost/omekaapi/api/archivegroup"
    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."archivegroup.php"; ?>">
        <?php echo $config['base_url']."archivegroup.php"; ?>
    </a>
    <br> <br>
      9. คลังเอกสารอิเล็กทรอนิกส์ / page=หน้าที่ , limitperpage=จำนวนรายการต่อหน้า  ([limitperpage=10],[cates=10,12,17 หรือ 0 ทั้งหมด] ,search=คำค้น,sort_by=title หรือ date ,sort_order=desc หรือ asc, [media_types= book,image,vdo,sound,doc หรือ 0  ทั้งหมด] )
      ตัวอย่าง url: "http://localhost/omekaapi/api/archivelistfilter?page=1&limitperpage=10&cates=10,12,17,18&search=หมอชาวบ้าน&sort_by=title&sort_order=asc&media_types=0"
      <br>
    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."archivelistfilter.php?page=1&limitperpage=10&cates=10,12,17,18&search=ประเวศ&sort_by=title&sort_order=asc&media_types=0"; ?>">
        <?php echo $config['base_url']."archivelistfilter.php?page=1&limitperpage=10&cates=10,12,17,18&search=ประเวศ&sort_by=title&sort_order=asc&media_types=0"; ?>
    </a>
    <br> <br>
      10. ลองสำรวจจากชนิดของสื่อที่หลากหลาย explore-by-media
      ตัวอย่าง url: "http://localhost/omekaapi/api/statlist"
      <br>
    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."statlist.php"; ?>">
        <?php echo $config['base_url']."statlist.php"; ?>
    </a>
    <br> <br>
      11. เนื้อหาโดดเด่นที่คัดสรรจากทีมงาน ในเดือน staff-picks revm=4 จำนวนเดือนย้อนหลัง จากเดือนปัจจุบัน , limitpermonth=8  หรือ ถ้า 0 คือทั้งหมด",
      ตัวอย่าง url: "http://localhost/omekaapi/api/staffpicks?revm=4&limitpermonth=8"
      <br>
    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."staffpicks.php?revm=4&limitpermonth=8"; ?>">
        <?php echo $config['base_url']."staffpicks.php?revm=4&limitpermonth=8"; ?>
    </a>
    <br> <br> <br>
      12. รวมเนื้อหายอดนิยม  รายปี หรือ ถ้า toplimit= 0 คือทั้งหมด
      ตัวอย่าง url: "http://localhost/omekaapi/api/popularall?revm=3&toplimit=0"
      <br>
    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."popularall.php?revm=3&toplimit=10"; ?>">
        <?php echo $config['base_url']."popularall.php?revm=3&toplimit=10"; ?>
    </a>
    <br> <br> <br>
      13. **A แสดงรายการ คอลเลกชั่น และ [ Class =  Collection,Dataset,Event,Image]
      ตัวอย่าง url: "http://localhost/omekaapi/api/archivegroupfilter?class=Collection,Dataset,Event,Image"
      <br>
    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."archivegroupfilter.php?class=Collection,Dataset,Event,Image"; ?>">
        <?php echo $config['base_url']."archivegroupfilter.php?class=Collection,Dataset,Event,Image"; ?>
    </a>
    <br> <br> <br>
      14. **B แสดงรายการ property
      ตัวอย่าง url: "http://localhost/omekaapi/api/archiveproperties"
      <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."archiveproperties.php"; ?>">
        <?php echo $config['base_url']."archiveproperties.php"; ?>
    </a>
    <br> <br> <br>
      15. **แสดงรายการ คอลเลกชั่น และ [ Class =  Collection,Dataset,Event,Image],[search=สสส] ,[Collection=10,12,13,14  ex. 10=สิ่งแวดล้อม,12=สุขภาพ,13=การศึกษา ดูจาก id=13 **B แสดงรายการ คอลเลกชั่น] , [property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents  ดูจาก id=14 **B แสดงรายการ property]
      <br>
      ตัวอย่าง url: "http://localhost/omekaapi/api/archivelistall?class=Collection,Dataset,Event,Image&search=สสส&collection=10,12,13,14&property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents&page=1&limitperpage=100"

    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."archivelistall.php?class=Collection,Dataset,Event,Image&search=สสส&collection=10,12,13,14&property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents&page=1&limitperpage=100"; ?>">
        <?php echo $config['base_url']."archivelistall.php?class=Collection,Dataset,Event,Image&search=สสส&collection=10,12,13,14&property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents&page=1&limitperpage=100"; ?>
    </a>
    <br> <br> <br>

      #========================#

      <br>
      16. แสดงรายการข้อมูล ส่วนที่เป็นข้อมูล Visibility=Public [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=50]",
      ตัวอย่าง url: "http://localhost/omekaapi/api/archivelistallpage?page=1&limitperpage=50"
      <br>
    <br>
    <br>
    <a target="_blank" href="<?php echo $config['base_url']."archivelistallpage.php?page=1&limitperpage=50"; ?>">
        <?php echo $config['base_url']."archivelistallpage.php?page=1&limitperpage=50"; ?>
    </a>
    <br> <br> <br>
      17.
      **แสดงรายการ ข้อมูล ส่วนที่เป็นข้อมูล Visibility=Public หาจาก ID [id ที่ต้องการหา-> id=2194,5865]
      ตัวอย่าง url: "http://localhost/omekaapi/api/archivelistallpagebyid?id=2194,5865"
   <!-- The Body tag is used to display the content on a web page which is specify between the body tag.  -->  
   </center>  
   </BODY>  
   </HTML> 

