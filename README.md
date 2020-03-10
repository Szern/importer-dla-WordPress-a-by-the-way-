# importer-dla-WordPress-a-by-the-way-
Bardzo prosty skrypt.

Załóżmy, że mamy sporo treści w pliku tekstowym, którą chcemy umieścić w formie postów w Wordpressie. Powiedzmy, że jest tych akapitów kilka tysięcy. W dodatku chcielibyśmy, aby nie były opublikowane jednocześnie.

Z pomocą przychodzi poniższy skrypt. Wrzucamy go do głównego folderu WP i odpalamy.
Należy dokładnie czytać opisy - skrypt nie jest odporny na błędy we wpisywanych danych.
Plikiem wejściowym jest plik tekstowy (z notatnika lub zapisany w Worda jako txt).
Skrypt importuje tekst na jeden ze sposobów:
- każdy akapit jest tytułem posta i treścią (identyczne tytuł i treść posta),
- naprzemiennie: jeden akapit jest tytułem, a drugi treścią posta (możliwa zmiana kolejności i),
- z każdego akapitu tworzy tytuł i treść posta (można zdefiniować łańcuch rozdzielający te treści i kolejność).

Możemy zdefiniować datę początkową, jeśli je nie zdefiniujemy, skrypt zacznie publikację od zaraz, z losowym przedziałem czasowym. Jeśli podamy mu np. od 2 do 10 godzin, to każdy następny post będzie publikowany nie wcześniej n iż dwie godziny po poprzednim i nie później niż 10 godzin po poprzednim.

Skrypt tworzy kategorie nazwane literami alfabetu i przyporządkowuje do nich posty na zasadzi - pierwsza litera w akapicie to kategoria posta.

Skrypt jest surowy, pewnie zawiera błędy, ale działa, również z WP 3.0.

projekt zamrozony, wersja z 2011 roku
