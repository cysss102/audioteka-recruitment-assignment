Na wstępie chciałem zaznaczyć, że kod, który "naprawiałem", 
chciałem w miarę możliwości dostosować do arhcitektury projektu co nie oznacza, że jest on ostateczny.
Z braku czasu (zadanie wykonywałem w weekend) byłem zmuszony przyspieszyć wykonanie tego zadania.

CODE REVIEW / ZMIANY, KTÓRE MOŻNA BYŁOBY WDROŻYĆ:

- przejście na atrybuty tam gdzie to nie zostało jeszcze zrobione
- używanie phpstan/php csfixera
- pokrycie kodu testami (dodatkowo można byłoby pokusić się o php infection)
- response builders - dane trzymać w obiektach(modelach), które będziemy zwracać (nie operować na tablicach)
- transakcje - nie wszędzie są transakcje (na początku w ogóle nie było)
- walidacja danych wejściowych - można byłoby napisać kod dot. walidacji danych, ewentualnie posłużyć się DTOsami i transformerami tak aby wyciągać z requestu dane i według wskazanych pól definiować co powinno być pobierane
- ogólnie całość można by wydzielić do różnych warstw/katalogów
- wdrożyć logowanie tam gdzie to potrzebne (logowanie, że proces przeszedł, logowanie błędów itd)
