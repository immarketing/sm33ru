#SELECT * from jos_vm_category_xref INTO OUTFILE 'jos_vm_category_xref.sql';
#SELECT * from jos_vm_product_category_xref INTO OUTFILE 'jos_vm_product_category_xref.sql';
#SELECT * from jos_vm_category INTO OUTFILE 'jos_vm_category.sql';
#SELECT * from jos_vm_product INTO OUTFILE 'jos_vm_product.sql';
#SELECT * from jos_vm_product_price INTO OUTFILE 'jos_vm_product_price.sql';

mysql -u suupermarkt -pMarKt3687Jhhdj suupermarkt

delete from jos_vm_category_xref;
delete from jos_vm_product_category_xref ;
delete from jos_vm_category ;
delete from jos_vm_product ;
delete from jos_vm_product_price ;
quit

mysql --default-character-set=Utf8 -u suupermarkt -pMarKt3687Jhhdj suupermarkt <cats.sql

LOAD DATA INFILE 'jos_vm_category_xref.sql' INTO TABLE jos_vm_category_xref;

\ cats.sql;

select product_id,product_thumb_image, product_full_image from jos_vm_product;
update jos_vm_product set product_thumb_image=concat(product_id,'.jpg'), product_full_image=concat(product_id,'.jpg');

update jos_vm_product set product_thumb_image=product_full_image;

