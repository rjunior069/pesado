const express=require("express");

const app = express();

app.get("/", function(req, res){
    res.send("Welcome to my app!");

});

app.get("/sobre", function(req, res){
    res.send("Minha pagina Sobre");
});

app.get("/blog",function(req, res){
    res.send("Welcome to my Blog");
});

app.get('/ola/:nome/:cargo/:cor',function(req, res){
    res.send("<h1>Ola  "+req.params.nome+"<h1/> " + "<h2> Seu cargo e: "+req.params.cargo+"<h2/>" + "<h3> Sua cor e: "+req.params.cor+"<h3/>");
//send sóm poder ser enviado uma vez por rota ou função
});


app.listen(8081, function(){

    console.log("Servidor rodandona url informada http:/localhost:8081");

});

