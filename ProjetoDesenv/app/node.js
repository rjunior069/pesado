const express=require("express");

const app = express();



app.get("/documentacoes", function(req, res){
    //   res.send("Minha pagina Documentacoes");
         res.sendFile(__dirname + "/html/documentacoes.html"); //linkando documentacoes.html ao meu app.
   });

app.get("/faleconosco", function(req, res){
    //   res.send("Minha pagina Faleconosco");
         res.sendFile(__dirname + "/html/faleconosco.html"); //linkando faleconosco.html ao meu app.
   });

app.get("/", function(req, res){
    //    res.send("Welcome to my app!");
          res.sendFile(__dirname + "/html/index.html"); //chamando caminho absoluto do index.html, evitando error app.
    });

app.get("/informacoes", function(req, res){
        //   res.send("Minha pagina informacoes");
             res.sendFile(__dirname + "/html/informacoes.html"); //linkando informacoes.html ao meu app.
       });

app.get("/inicio", function(req, res){
        //   res.send("Minha pagina inicio");
             res.sendFile(__dirname + "/html/inicio.html"); //linkando inicio.html ao meu app.
       });

app.get("/ondeestamos", function(req, res){
        //   res.send("Minha pagina ondeestamos");
             res.sendFile(__dirname + "/html/ondeestamos.html"); //linkando onde estamos.html ao meu app.
       });

app.get("/politicadeprivacidade", function(req, res){
        //   res.send("Minha pagina politicadeprivacidade");
             res.sendFile(__dirname + "/html/politicadeprivacidade.html"); //linkando politica de privacidadefaleconosco.html ao meu app.
       });

       
app.get("/sobre", function(req, res){
 //   res.send("Minha pagina Sobre");
      res.sendFile(__dirname + "/html/sobre.html"); //linkando sobre.html ao meu app.
});

app.get("/solucoes", function(req, res){
    //   res.send("Minha pagina Solucoes");
         res.sendFile(__dirname + "/html/solucoes.html"); //linkando solucoes.html ao meu app.
   });
  

app.get("/termodeuso", function(req, res){
    //   res.send("Minha pagina Solucoes");
         res.sendFile(__dirname + "/html/termodeuso.html"); //linkando termodeuso.html ao meu app.
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

//Imagens
app.get("/imagens", function(req, res){
    //   res.send("Minha pagina Solucoes");
         res.sendFile(__dirname + "/html/termodeuso.html"); //linkando termodeuso.html ao meu app.
});

