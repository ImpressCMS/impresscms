// A password quality meter
// Written by Gerd Riesselmann
// http://www.gerd-riesselmann.net
//############################################################################
// Modified and remodelled by Rodrigo Pereira Lima aka TheRplima
// http://www.brinfo.com.br
// therplima@gmail.com
// Última Atualização: 16/09/2006
//############################################################################
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// This program uses code from mozilla.org (http://mozilla.org)

//Na inicialização ativa a barra de qualidade.
//Caso já exista um função onload programada armazena o valor dela aqui e executa tb.
if (window.onload){
  var oldonload = window.onload;
  window.onload=function(){
    oldonload();
    initQualityMeter();
  }
}else{
  window.onload=function(){
    initQualityMeter();
  }
}

//init
function initQualityMeter(change){
	var _pwd = document.getElementById(passField);
	if (!change && tipo != 1){
		var change = 0;
	}else if (!change && tipo == 1){
		var change = 1;
	}else if (change && tipo == 0){
		var change = change;
	}else if (change && tipo == 1){
		var change = change;
	}
	if (change == 1){
		_pwd.disabled = false;
		document.getElementById('vpass').disabled = false;
		updateQualityMeter();
		if (_pwd){
			document.forms["userinfo"][passField].onkeyup = updateQualityMeter;
		}
	}else if (change == 0){
		var sbtn = document.forms["userinfo"]["submit"];
		sbtn.disabled = false;
		_pwd.value = '';
		_pwd.disabled = true;
		document.getElementById('vpass').value = '';
		document.getElementById('vpass').disabled = true;
	}
}


function updateQualityMeter(){
	var quality = getPasswordStrength();
	setCount(quality);
}

// Function taken from Mozilla Code:
// http://lxr.mozilla.org/seamonkey/source/security/manager/pki/resources/content/password.js
function getPasswordStrength(){
    //Expressões regulares usadas para a verficação do password.
    //Estão sendo passadas por campos hidden do formulário para evitar incompatibilidade
    var regex  = document.forms["userinfo"]["regex"].value;
    var regex1 = document.forms["userinfo"]["regex1"].value;
    var regex2 = document.forms["userinfo"]["regex2"].value;
    var regex3 = document.forms["userinfo"]["regex3"].value;
    var regex4 = document.forms["userinfo"]["regex4"].value;
    var regex5 = document.forms["userinfo"]["regex5"].value;

    //Dados do usuário usados na verficação
    if (tipo == 1)
      var uname = document.forms["userinfo"]["uname"].value;
    else
      var uname = tipo;
    if (tipo1 == 1)
      var email = document.forms["userinfo"]["email"].value;
    else
      var email = tipo1;
    var pw    = document.forms["userinfo"][passField].value;

    //Botão do formulário para controlar o envio.
    var sbtn = document.forms["userinfo"]["submit"];

	//Armazena o valor do comprimento da String da senha
	var pwlength = (pw.length);
	//Caso o comprimento seja maior do que 6 ele passa a ser 6
	if (pwlength > minpass){
      pwlength = minpass;
	}
    
    //checa se o nome de usuário está contido na string
    //ele pega o nome do usuário case insensite, e tanto faz se o
    //esta no inicio, meio ou fim da palavra, o que ele pega é o nome em si.
    //O que acontece quando o nome do usuário é encontrado na string?
    //O script desconsidera esses caracteres na hora de contar a qtde de caracteres
    //da string diminuindo assim sua qualidade.
    //Alguns exemplos de senha que serão pegas:
    //nome do usuário = admin
    //senha = AdMiN123 - pega!
    //senha = 123AdMiN123 - pega!
    //senha = 123ADMIN - pega!
    //senha = 1A2D3M4I5N - não pega!
    // Acho que deu pra entender ;)
    if (uname.length > 0){
      var contem = 0;
      for (i = 0; i <= (pw.length)-1; i++){
        if (pw.substr(i,uname.length).toUpperCase() == uname.toUpperCase())
          contem++;
      }

      if (contem > 0){
        pwlength = pwlength-uname.length;
      }
    }
    
    //checa se partes do email do usuário está contido na string
    //ele pega as partes do email do usuário case insensite, e tanto faz se o
    //esta no inicio, meio ou fim da palavra, o que ele pega são as partes do email em si.
    //Entenda como partes do email o que tem antes do @ e o nome do dominio sem o .xxx
    //O que acontece quando o nome do usuário é encontrado na string?
    //O script desconsidera esses caracteres na hora de contar a qtde de caracteres
    //da string diminuindo assim sua qualidade.
    //Alguns exemplos de senha que serão pegas:
    //nome do usuário = admin
    //senha = AdMiN123 - pega!
    //senha = 123AdMiN123 - pega!
    //senha = 123ADMIN - pega!
    //senha = 1A2D3M4I5N - não pega!
    // Acho que deu pra entender ;)
    if (email.length > 0){
      var contememail = 0;
      var emailarr = email.split("@");
      if (emailarr.length > 1){
        for (i = 0; i <= (pw.length)-1; i++){
          if (pw.substr(i,emailarr[0].length).toUpperCase() == emailarr[0].toUpperCase())
            contememail++;
        }

        if (contememail > 0){
          pwlength = pwlength-emailarr[0].length;
        }

        contememail = 0;
        emailarr = emailarr[1].split(".");
        for (i = 0; i <= (pw.length)-1; i++){
          if (pw.substr(i,emailarr[0].length).toUpperCase() == emailarr[0].toUpperCase())
            contememail++;
        }

        if (contememail > 0){
          pwlength = pwlength-emailarr[0].length;
        }
      }
    }
    
	//Verifica o uso de números na senha
	//Caso encontre números sequenciais repetidos tipo 111 ou 22 ou 3333
	//retira do comprimento da string a quantidade de números repetidos
	//diminuindo assim a qualidade da string
    var re = new RegExp(regex, "g");
    var regNum = new RegExp(regex3, "g");
	var numnumeric = pw.replace(re,'');
    var numok = numnumeric.replace(regNum,'');
    var repet = numnumeric.length-numok.length;

    var numeric= numok.length-repet;

    if (repet > 0){
      var percent = Math.round((repet / pw.length)*100);
      if (percent >= 60){
        pwlength = pwlength-repet;
      }else if(percent > 20 && percent < 60){
        pwlength = (pwlength-repet)+1;
      }else if(percent <= 20){
        pwlength = pwlength;
      }
    }
    
    if (numnumeric.length == pw.length){
      pwlength = 2;
    }
    
	if (numeric>3)
	  numeric=3;

	//Verifica o uso de symbols na senha
	//Caso encontre simbolos sequenciais repetidos tipo $$$ ou *** ou %%%
	//retira do comprimento da string a quantidade de números repetidos
	//diminuindo assim a qualidade da string
	var re1 = new RegExp(regex1, "g");
	var regSim = new RegExp(regex4, "g");
	var symbols = pw.replace(re1,'');
	var simok = symbols.replace(regSim,'');
	var simrepet = symbols.length-simok.length;
	
	var numsymbols= simok.length-simrepet;
	
    if (simrepet > 0){
      var simpercent = Math.round((simrepet / pw.length)*100);
      if (simpercent >= 60){
        pwlength = pwlength-simrepet;
      }else if(simpercent > 20 && simpercent < 60){
        pwlength = (pwlength-simrepet)+1;
      }else if(simpercent <= 20){
        pwlength = pwlength;
      }
    }

    if (numsymbols.length == pw.length){
      pwlength = 2;
    }

	if (numsymbols>3)
	  numsymbols=3;

	//Verifica o uso de letras maiusculas na senha
	//Caso encontre letras maiusculas sequenciais repetidas tipo AAA ou BB ou RRRR
	//retira do comprimento da string a quantidade de números repetidos
	//diminuindo assim a qualidade da string
	var re2 = new RegExp(regex2, "g");
	var regUp = new RegExp(regex5, "g");
	var numupper = pw.replace(re2,'');
	var upOk = numupper.replace(regUp,'');
	var uprepet = numupper.length-upOk.length;

    var upper= upOk.length-uprepet;

    if (uprepet > 0){
      var upperpercent = Math.round((uprepet / pw.length)*100);
      if (upperpercent >= 60){
        pwlength = pwlength-uprepet;
      }else if(upperpercent > 20 && upperpercent < 60){
        pwlength = (pwlength-uprepet)+1;
      }else if(upperpercent <= 20){
        pwlength = pwlength;
      }
    }

    if (numupper.length == pw.length){
      pwlength = 2;
    }
    
	if (upper>3)
	  upper=3;

    //Calcula a porcentagem da barra de qualidade com base nos passos checados anteriomente
    var pwstrength=((pwlength*10)-20) + (numeric*10) + (numsymbols*15) + (upper*10);

    //Se senha é menor do que o mínimo configurado desabilitar botão de submit
    if (pwlength < minpass){
      sbtn.disabled = true;
    }else
    //Se o padrão de qualidade da senha for menor que o configurado desabilitar botão de submit
    if (pwstrength < pass_level){
      sbtn.disabled = true;
    }else
    //Se segurança e o comprimento da senha maior que os níveis configurados libera o botão
    //do formulário permitindo a realização do cadastro
    if (pwstrength > pass_level && pwlength >= minpass){
      sbtn.disabled = false;
    }

	//Garantindo que o valor da porcentagem da barra fique sempre entre 0 e 100
	if (pwstrength < 0){
  	  pwstrength = 0;
	}
	if (pwstrength > 100){
	  pwstrength = 100;
	}

	return pwstrength;
}
