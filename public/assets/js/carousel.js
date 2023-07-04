$(document).ready(function() {

(function() {
    this.film_rolls || (this.film_rolls = []);
    this.film_rolls['demo'] =new FilmRoll({
        container:'#demo',
        height: 400,
    });
    return true;
}
).call(this)
});
    

