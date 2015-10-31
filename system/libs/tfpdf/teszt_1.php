<?php

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

require('tfpdf.php');
require('grid.php');

//$pdf = new tFPDF();

$pdf = new PDF_Grid();
$pdf->grid = true;
$pdf->AddPage();

// Add a Unicode font (uses UTF-8)
$pdf->AddFont('Arial','','arial.ttf',true);
$pdf->AddFont('ArialBold','','arialb.ttf',true);
$pdf->AddFont('ArialItalic','','arial_i.ttf',true);
//$pdf->AddFont('Arial','I','arial.ttf',true);
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$sor0 = 3.5; //8-es betűméretű sor magassága
$sor1 = 4; //9-es betűméretű sor magassága
$beh = 6; // sor eleji behúzás


$pdf->Rect(160,11,6,6);		
$pdf->Rect(166,11,6,6);		
$pdf->Rect(172,11,6,6);		
$pdf->Rect(178,11,6,6);		
$pdf->Rect(184,11,6,6);	

$pdf->SetFont('ArialBold','',13);
$pdf->SetXY(10,17);
$pdf->Cell(0,10,'Belépési Nyilatkozat',0,1,'C');

$pdf->Ln(4);

$pdf->SetFont('Arial','',9);
//$pdf->SetXY(10,50);
$pdf->Cell($beh);
//$pdf->Cell(0,0,'Alulírott, a mai napon kijelentem, hogy a MULTI JOB Iskolaszövetkezetbe (Címünk: 1137 Budapest, Szent István krt. 2. I/2.)');
$pdf->Cell(58,$sor1,'Alulírott, a mai napon kijelentem, hogy a ',0,0,'L');
$pdf->SetFont('ArialBold','',9);
$pdf->Cell(50,$sor1,'MULTI JOB Iskolaszövetkezetbe ',0,0,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,$sor1,'(Címünk: 1137 Budapest, Szent István krt. 2. I/2.)',0,1,'L');
$pdf->Cell(0,$sor1,'tagként kívánok belépni.',0,1,'L');

//$pdf->Ln(4);

$pdf->Cell($beh);		
$pdf->Cell(0,$sor1,'Ezen belépési nyilatkozat aláírásával egyidejűleg kijelentem, hogy a Szövetkezet Alapszabályát, Szervezeti Működési',0,1,'L');
$pdf->Cell(0,$sor1,' Szabályzatát magamra nézve kötelező érvényűnek elfogadom.',0,1,'L');
$pdf->Cell($beh);		
$pdf->Cell(0,$sor1,'A szövetkezet feladatainak megvalósításában személyes munkámmal kívánok részt venni.',0,1,'L');
$pdf->Cell($beh);		
$pdf->Cell(0,$sor1,'A szövetkezet tartozásaiért vagyoni hozzájárulásom (megváltott részjegy) erejéig felelősséget vállalok.',0,1,'L');
$pdf->Cell($beh);
$pdf->Cell(0,$sor1,'Vállalom, hogy a jelen nyilatkozatban megjelölt lakcímem megváltozását 5 munkanapon belül a Multi Job Iskolaszövetkezetnek',0,1,'L');
$pdf->MultiCell(0,$sor1,'bejelentem. Amennyiben ezt elmulasztom és ezzel az Iskolaszövetkezetnek többletköltséget okozok, azt az én kötelességem viselni.');
$pdf->MultiCell(0,$sor1,'Tudomásul veszem, hogy amennyiben a címváltozás bejelentésének elmaradása miatt az év elején az Iskolaszövetkezet által a személyi jövedelemadó bevallásához – igazoltan – elküldött igazolása nem érkezik meg hozzám, azért és a bevallás elmaradásáért a felelősség kizárólag engem terhel.');

$pdf->Ln(2);
$pdf->SetFont('ArialBold','',11);
$pdf->Cell(0,$sor1,'Büntetőjogi felelősségem tudatában kijelentem, hogy az alábbi adatok megfelelnek a valóságnak.',0,1,'C');
$pdf->Ln(2);

$pdf->SetFont('Arial','',9);
$pdf->Cell($beh);		
$pdf->Cell(0,$sor1,'Jelen nyilatkozat aláírásával hozzájárulásomat adom, hogy a személyi adataimat igazoló – a jelen nyilatkozatban feltüntetett –',0,1,'L');
$pdf->MultiCell(0,$sor1,'okmányaimat a Multi Job Iskolaszövetkezet lefénymásolja, a fénymásolat és jelen nyilatkozat által rögzített adataimat a munkaviszonyaimmal összefüggő célokra felhasználja és tárolja.' . "\n" . 'Hozzájárulok, hogy adataimat a Multi Job Iskolaszövetkezet személyi azonosításra nem alkalmas módon statisztikai célokra felhasználja, továbbadja.');
$pdf->Cell($beh);		
$pdf->Cell(0,$sor1,'Büntetőjogi felelősségem tudatában kijelentem – és aláírásommal megerősítem – azt is, hogy a személyi adataimnál (az 1.',0,1,'L');
$pdf->MultiCell(0,$sor1,'pontban) feltüntetett bankszámlaszámot én jelöltem meg, azt legjobb tudásom, gondosságom alapján helyesen adtam meg. A bankszámlaszám hibás megjelöléséből származó minden anyagi kárért a felelősség és az ennek korrigálásával járó adminisztratív feladatok elvégzésével járó minden felelősség kizárólag engem terhel.');



$pdf->Ln(9);
$pdf->SetFont('ArialBold','',12);
$pdf->Cell(0,$sor1,'1. Személyi adatok:',0,1,'L');
$pdf->Ln(3);
$pdf->Cell(12,$sor1,'Név:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(70,$sor1,'Kovács kettő János',0,0,'L');
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(41,$sor1,'Anyja neve (leánykori):',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'Sipákovics Amália Zsanett',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(28,$sor1,'Születési hely:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(53.5,$sor1,'Máriapócs',0,0,'L');
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(25,$sor1,'Születési idő:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'1986 augusztus 31.',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(40,$sor1,'Diákigazolvány szám: ',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'543473543545345534',0,1,'L');

$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$sor1,'(-ha van vonalkód az alatta lévő szám, -újon a kártyaszám, -ideiglenesen az igazolás sorszámát)',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(20,$sor1,'TAJ szám:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(62,$sor1,'54534343434',0,0,'L');
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(30,$sor1,'Állampolgárság:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(100,$sor1,'Magyar',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(33,$sor1,'Adóazonosító jel:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'54534343434',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(35,$sor1,'Bankszámla-szám:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'545-34343-6546434',0,1,'L');

$pdf->Ln(2);
$pdf->SetFont('ArialBold','',11);
$pdf->Cell(0,$sor1,'CSAK SAJÁT NÉVRE SZÓLÓ BANKSZÁMLASZÁMOT FOGADUNK EL!',0,1,'C');
$pdf->Ln(2);

$pdf->SetFont('ArialBold','',10);
$pdf->Cell(46,$sor1,'Számlavezető bank neve:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'Budapest bank',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(30,$sor1,'Állandó lakcím:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'Budapest, Valami utca 12.',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(32,$sor1,'Elérhetőségi cím:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'Budapest, Valami utca 12.',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(22,$sor1,'E-mail cím:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,$sor1,'valami@email.hu',0,0,'L');
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(23,$sor1,'Mobil szám:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'546544654',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->MultiCell(0,$sor1,'Büntetőjogi felelősségem tudatában kijelentem hogy, nappali tagozatos hallgatója vagyok az alábbi oktatási intézménynek.');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(70,$sor1,'Eddigi legmagasabb iskolai végzettség:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'középiskolai végzettség',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(70,$sor1+1,'Jelenlegi oktatási intézmény (jelenlegi) neve, pontos címe:',0,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,$sor1,'Széchenyi István középiskola weafsdfa sdf asdf asdf asdfa sdf sdf sdfg sdf gsdfg sdfgsd jjélfkg sdléfjk gslédkfj gsdkljf gklsdjfg.');


$pdf->Ln(15);

$pdf->SetFont('ArialBold','',10);
$pdf->Cell(65,$sor1,'Belépés dátuma (a Multi Job ISz-be):',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,$sor1,'tttttt',0,0,'L');
$pdf->SetFont('ArialBold','',10);
$pdf->Cell(30,$sor1,'(Kilépés dátuma:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$sor1,'...............)',0,1,'L');

// Load a UTF-8 string from a file and print it
//$txt = file_get_contents('HelloWorld.txt');
//$pdf->Write(8,$txt);


// második oldal -------------------------

$pdf->AddPage();

$pdf->SetFont('ArialBold','',12);
$pdf->Cell(0,$sor1,'2. Vagyoni hozzájárulás:',0,1,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(0,$sor1,'Kötelezem magam, hogy a belépést követő első munkabéremből az 1.000.Ft (azaz egyezer forint) értékű részjegyet megváltom. Kilépéskor, kizáráskor a vagyoni hozzájárulást a szövetkezet visszafizeti.');
$pdf->Cell(0,$sor1,'Szövetkezettel kötött megállapodást, a mai naptól számítva magamra nézve kötelező érvényűnek tekintem.',0,1,'L');
$pdf->Ln(2);
$pdf->SetFont('ArialBold','',9);
$pdf->MultiCell(0,$sor1,'Kijelentem, hogy az általam megadott e-mail címre küldött hivatalos leveleket, szerződéseket, bérjegyzéket, M30-as személyi jövedelemadó igazolást, hivatalosan megküldöttnek tekintem, valamint munkáajánlatokat és munkákkal kapcsolatos fontos információkat, egyéb hasznos  tudnivalókat küldhet!');
$pdf->Ln(2);
$pdf->MultiCell(0,$sor1,'Tudomásul veszem, hogy a levont személyi jövedelemadóból alapesetben nem vagyok jogosult családi adókedvezményre vagy súlyos fogyatékos magánszemélyek személyi kedvezményére. ');
$pdf->Ln(2);
$pdf->SetFont('ArialItalic','',9);
$pdf->MultiCell(0,$sor1,'(Ha jogosult bármelyikre, igényeljen hozzá nyilatkozatot belépésnél, mert az adóelőleg számításánál csak akkor tudjuk figyelembe venni e kedvezményeket, ha érvényes nyilatkozatot ad le a magánszemély! )');
$pdf->Ln(2);
$pdf->SetFont('ArialBold','',9);
$pdf->MultiCell(0,$sor1,'A nyilatkozat tartalmát érintő bármely változás esetén, köteles vagyok haladéktalanul új nyilatkozatot tenni, vagy a korábbi nyilatkozatot visszavonni. ');
$pdf->Ln(2);
$pdf->SetFont('ArialItalic','',9);
$pdf->MultiCell(0,$sor1,'(Amennyiben a nyilatkozattételkor fennálló körülmények ellenére a személyi – vagy családi kedvezmény érvényesítését jogalap nélkül kéri, aminek következtében az adóbevallása alapján 10 ezer forintot meghaladó befizetési különbözet mutatkozik, a befizetési különbözet 12 százalékát különbözeti-bírságként kell megfizetnie! )');

$pdf->Ln(11);

$pdf->SetFont('ArialBold','',12);
$pdf->Cell(100,$sor1,'Budapest, ................................',0,0,'L');
$pdf->Line(110,101,150,101);
$pdf->SetXY(123,103);
$pdf->SetFont('ArialBold','',11);
$pdf->Cell(0,$sor1,'aláírás',0,1,'L');



$pdf->Ln(7);
$pdf->SetFont('ArialBold','',12);
$pdf->Cell(0,$sor1,'Fontos tudnivalók',0,1,'L');

$pdf->Ln(3);
$pdf->SetFont('Arial','',8);
$pdf->Cell(6,$sor0,'1.',0,0,'L');
$pdf->MultiCell(0,$sor0,'A munkavállaló tudomásul veszi, hogy az őt érintő munkaviszonyból eredő munkáltatói jogokat és kötelességeket Szücs Róbert elnök – mint munkáltató gyakorolja, illetve teljesíti. ');
$pdf->Ln(2);
$pdf->Cell(6,$sor0,'2.',0,0,'L');
$pdf->MultiCell(0,$sor0,'Tagsági viszonyt létesítésnek feltételei: iskolalátogatási igazolás, személyi igazolvány szám, belépési nyilatkozat, részjegy, adószám, TAJ-szám (külföldi állampolgárságú diák munkavállalónál: nappalis iskolalátogatási igazolás, a Magyarországon való jogszerű tartózkodást biztosító – magyar hatóság által kiadott - hatósági engedély száma, belépési nyilatkozat, részjegy, belföldi kézbesítési cím vagy kézbesítési megbízott, adószám és ha van TAJ-szám).');
$pdf->Ln(2);
$pdf->Cell(6,$sor0,'3.',0,0,'L');
$pdf->MultiCell(0,$sor0,'A munkaviszony határozott időre jön létre, minden egyes munkához külön munkaszerződést kell kötni. ');
$pdf->Ln(2);
$pdf->Cell(6,$sor0,'4.',0,0,'L');
$pdf->MultiCell(0,$sor0,'A munkavállaló személyi alapbérét, munkakörét és a munkavégzés helyét az aktuális diákmunka szerződés tartalmazza. ');
$pdf->Ln(2);
$pdf->Cell(6,$sor0,'5.',0,0,'L');
$pdf->MultiCell(0,$sor0,'A munkarendet a Multi Job ISz. határozza meg. A napi munkaidő nem haladhatja meg a 12 órát és annak 8 hét átlagában a teljes munkaidőnek meg kell felelnie. Tudomásul veszem, hogy 18 év alatti munkavállalóként nem dolgozhatok napi 8 óránál többet!');
$pdf->Ln(2);
$pdf->Cell(6,$sor0,'6.',0,0,'L');
$pdf->MultiCell(0,$sor0,'Az elvégzett munka után járó munkabért, a tárgyhót követő hónap 10. napjáig, banki átutalással kifizeti a munkaadó. Az egyéb juttatás feltételeit a Szövetkezet anyagi érdekeltségi rendszere határozza meg.
Az 1997. évi LXXX. tv. (Tbj. tv.) 5. § (1) bekezdés b) pontja alapján a diákbér társadalombiztosítási (azaz nyugdíj- és egészségbiztosítási) járulékalapot NEM képező jövedelem. 
Az 1999. évi 47. APEH iránymutatás alapján 27%-os mértékű EHO-t NEM kell megfizetni a diákbér után.');
$pdf->Ln(2);
$pdf->Cell(6,$sor0,'7.',0,0,'L');
$pdf->MultiCell(0,$sor0,'A munkavállaló közvetlen szakmai felettese az aktuális projekt vezető.');
$pdf->Ln(2);
$pdf->Cell(6,$sor0,'8.',0,0,'L');
$pdf->MultiCell(0,$sor0,'A munkavállaló kötelezettséget vállal arra, hogy a feladatkörébe tartozó munkát lelkiismeretesen elvégzi, a kapott utasításokat teljesíti, betartja a munkavédelmi rendelkezéseket és előírásokat, valamint a munkafegyelemre vonatkozó egyéb szabályokat. Károkozás, vagy munkafegyelem megsértése esetén felelősséggel tartozik.');
$pdf->Ln(2);
$pdf->SetFont('ArialBold','',8);
$pdf->Cell(6,$sor0,'9.',0,0,'L');
$pdf->MultiCell(0,$sor0,'A bérkifizetés feltétele, hogy az ID számmal ellátott teljesítési igazolásokat minden munkavégzést követő 5 munkanapon belül a munkavállaló eljuttassa az aktuális projektvezetőhöz!');
$pdf->Ln(2);
$pdf->SetFont('Arial','',8);
$pdf->Cell(6,$sor1,'10.',0,0,'L');
$pdf->MultiCell(0,$sor0,'Külföldi állampolgárságú munkavállaló esetében 
      Nyilatkozat:
      Kijelentem, hogy az adóévben jövedelmeimről adóbevallást nyújtok be. A külszolgálatra, külföldi kiküldetésre tekintve kapott összeg adóköteles részének megállapítására az általános – az átmeneti szabály alkalmazását választom.* A megfelelő szövegrész aláhúzandó. ');
$pdf->Ln(2);
$pdf->Cell(6,$sor0,'11.',0,0,'L');
$pdf->MultiCell(0,$sor0,'Nyilatkozat munkavédelmi és tűzvédelmi oktatásról
Teljes felelősségem tudatában nyilatkozom, hogy a mai napon megtartott munkavédelmi és tűzvédelmi oktatás teljes körű anyagát munkakörömhöz kapcsolódó általános és speciális ismereteket megkaptam, azokat a munkavégzés során alkalmazom és betartom.');
$pdf->Ln(2);
$pdf->SetFont('ArialBold','',8);
$pdf->Cell(6,$sor0,'12.',0,0,'L');
$pdf->MultiCell(0,$sor0,'A Felek jelen szerződés aláírásával kifejezetten és egybehangzóan megállapodnak, hogy a Munkáltató jogosult a Munkavállalót a munkaszerződés alapján megillető mindenkori munkabére nettó 1,7 %-át, de bérfizetésenként legalább 300,-Ft (háromszáz forint) összeget, maximum 1000,-Ft (ezer forint) a Kandó Kámán Műszaki Főiskola Multi Job Iskolaszövetkezet működési költéseihez történő tagi hozzájárulásként a munkabér kifizetését megelőzően levonni. Munkavállaló kijelenti, hogy a tagi hozzájárulás felhasználásáról a Munkáltató megfelelően tájékoztatta és ezen megállapodást ennek ismeretében kötötte. ');




$pdf->Ln(10);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,$sor1,'');





$pdf->Output();
?>