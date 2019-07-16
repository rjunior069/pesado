const multer = require('multer');
// vamos exportar nosso modulo multer , o qual vamos executar as nossas configurações

module.exports = (multer({
//como deve ser feito o armazenamento dos arquivos?
storage:multer.diskStorage({
    //qual deve ser o destino deles?
    destination:(req, file, cb) =>{
        //setamos o destino como segundo paramentro do callbak
        cb(null, './app/imagens');

    },
    // E como devem se chamar?
    filename:(req, file, cb) => {
        //setamos o nome do arquivo que vai ser salvado no segundo parametro
        //Apenas concatenei a data atual como o nome original do arquivo, que a biblioteca nos disponibiliza
        cb(null, Date.now().toString() + '-' + file.originalname);
        
    }
}), //FIM DA CONFIGURAÇÃO DE ARMAZENAMENTO

}));

//IMPORTANMOS NOSSO MIDDLEWARE
const multer = require("./")