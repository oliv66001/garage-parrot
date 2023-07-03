/*$document(function() {
    $(function() {
        this.film_rolls || (this.film_rolls = []);
        this.film_rolls['demo'] =new FilmRoll({
            container:'#demo',

                height: 600,
                });
        return true;
    });
}).call(this);*/

$(document).ready(function() {

(function() {
    this.film_rolls || (this.film_rolls = []);
    this.film_rolls['demo'] =new FilmRoll({
        container:'#demo',
        height: 560,
    });
    return true;
}
).call(this)
});
    

