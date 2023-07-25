# omekaapi
api คลังข้อมูลดิจิทัล

เรียก ผ่าน localhost
http://localhost/omekaapi/api

1. เนื้อหาคัดสรร (ปี crty = 2023 , เดือน เมษายน crtm=04, จำนวนรายการต่อหน้า limitpermonth=10) [crty=yyyy,crtm=mm,limitpermonth=n]",
ตัวอย่าง url: "http://localhost/omekaapi/api/monthlylist?crty=2023&crtm=04&limitpermonth=10"

2. เนื้อหายอดนิยม 5 รายการ hits สูงสุด toplimit [n]
ตัวอย่าง url: "http://localhost/omekaapi/api/popularlist?toplimit=10"

3. หารายการ ของปี ย้อนสำรวจความทรงจำสำคัญ ที่เคยเกิดขึ้น ในเดือน เมษายน (เมื่อ 20 ปีที่แล้ว) skipy=20, เดือน skipm=00 ทุกเดือน/ limitperpage=10 ต่อหน้า [skipy=20, skipm=04, limitperpage=10]",
ตัวอย่าง url: "http://localhost/omekaapi/api/flashbacklist?skipy=20&skipm=04&limitperpage=10"

4. ชวนออกสำรวจ ตามหัวเรื่อง เลือกสุ่มจำนวนหัวเรื่อง/ limit=10 จำนวนรายการ [limit=10]
ตัวอย่าง url: "http://localhost/omekaapi/api/letsgolist?limit=10"

5. เนื้อหารายละเอียด รายการ / id=2114  [id=2114]
ตัวอย่าง url: "http://localhost/omekaapi/api/item?id=2114"

6. คลังเอกสารอิเล็กทรอนิกส์ที่เกี่ยวข้อง / id=1966  [id=1966,limit=8]
ตัวอย่าง url: "http://localhost/omekaapi/api/relations?id=1966&limit=8"

7. คลังเอกสารอิเล็กทรอนิกส์ / page=หน้าที่ , limitperpage=จำนวนรายการต่อหน้า  [limitperpage=10]
ตัวอย่าง url: "http://localhost/omekaapi/api/archivelist?page=1&limitperpage=10"

8. หมวดหมู่ คลังเอกสารอิเล็กทรอนิกส์ 
ตัวอย่าง url: "http://localhost/omekaapi/api/archivegroup"

9. คลังเอกสารอิเล็กทรอนิกส์ / page=หน้าที่ , limitperpage=จำนวนรายการต่อหน้า  ([limitperpage=10],[cates=10,12,17 หรือ 0 ทั้งหมด] ,search=คำค้น,sort_by=title หรือ date ,sort_order=desc หรือ asc, [media_types= book,image,vdo,sound,doc หรือ 0  ทั้งหมด] )
ตัวอย่าง url: "http://localhost/omekaapi/api/archivelistfilter?page=1&limitperpage=10&cates=10,12,17,18&search=ประเวศ&sort_by=title&sort_order=asc&media_types=0"

10. ลองสำรวจจากชนิดของสื่อที่หลากหลาย explore-by-media
ตัวอย่าง url: "http://localhost/omekaapi/api/statlist"

11. เนื้อหาโดดเด่นที่คัดสรรจากทีมงาน ในเดือน staff-picks revm=4 จำนวนเดือนย้อนหลัง จากเดือนปัจจุบัน , limitpermonth=8  หรือ ถ้า 0 คือทั้งหมด",
ตัวอย่าง url: "http://localhost/omekaapi/api/staffpicks?revm=4&limitpermonth=8"

12. รวมเนื้อหายอดนิยม  รายปี
ตัวอย่าง url: "http://localhost/omekaapi/api/popularall?revm=3&toplimit=0"

13. **A แสดงรายการ คอลเลกชั่น และ [ Class =  Collection,Dataset,Event,Image]
ตัวอย่าง url: "http://localhost/omekaapi/api/archivegroupfilter?class=Collection,Dataset,Event,Image"

14. **B แสดงรายการ property
ตัวอย่าง url: "http://localhost/omekaapi/api/archiveproperties"

15. **แสดงรายการ คอลเลกชั่น และ [ Class =  Collection,Dataset,Event,Image],[search=สสส] ,[Collection=10,12,13,14  ex. 10=สิ่งแวดล้อม,12=สุขภาพ,13=การศึกษา ดูจาก id=13 **B แสดงรายการ คอลเลกชั่น] , [property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents  ดูจาก id=14 **B แสดงรายการ property]

ตัวอย่าง url: "http://localhost/omekaapi/api/archivelistall?class=Collection,Dataset,Event,Image&search=สสส&collection=10,12,13,14&property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents&page=1&limitperpage=100"