const express=require("express");

const app = express();

app.get("/", function(req, res){
//    res.send("Welcome to my app!");
      res.sendFile(__dirname + "/index.html"); //chamando caminho absoluto do index.html, evitando error app.
});

app.get("/sobre", function(req, res){
 //   res.send("Minha pagina Sobre");
      res.sendFile(__dirname + "/sobre.html"); //linkando sobre.html ao meu app.
});

app.get("/blog",function(req, res){
    res.send("Welcome to my Blog");
});

app.get('/ola/:nome/:cargo/:cor',function(req, res){
    res.send("<h1>Ola  "+req.params.nome+"<h1/> " + "<h2> Seu cargo e: "+req.params.cargo+"<h2/>" + "<h3> Sua cor e: "+req.params.cor+"<h3/>");
//send só pode ser enviar uma vez por rota ou função
});


app.listen(8081, function(){

    console.log("Servidor rodando na url informada http:/localhost:8081");

});

