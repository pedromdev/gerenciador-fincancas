/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var client = new Keen({
    projectId: "555f548196773d3304737178", // String (required always)
    writeKey: "d5e6a45536f139fbfbbde98948cf9af4d7025c05a87404ed928814f757348c78640fb050a93a2a72e5cef00bc73deb8ec6345c120ebd363b1185bcd65db2b57ed7947de5427fac7a5800f015636dbec2609859b57404d1e0c6e4b2d821ed63050fc73aed38e2c5d3bb394618644aeefb", // String (required for sending data)
    readKey: "7d6fc2d25ea67193d413c2d74790ecfee1393aae7bf768afd80a05c320f88d5579a8bb9cfc76b53ac59427b3f7e67e77e5841c75dde1c05d057e13e18eab83c631a4290703c694a64111a9b43be2a4f33f747c0bb1291bde6a240f81d9f67969240e8e8d4756fe9f6fe1a3b29065f44f", // String (required for querying data)
    protocol: "https", // String (optional: https | http | auto)
    host: "api.keen.io/3.0", // String (optional)
    requestType: "jsonp"            // String (optional: jsonp, xhr, beacon)
});

$keenData = {
    page: {
        title: document.title,
        host: document.location.host,
        href: document.location.href,
        path: document.location.pathname,
        protocol: document.location.protocol.replace(/:/g, ""),
        query: document.location.search
    },
    visitor: {
        referrer: document.referrer,
        ip_address: "${keen.ip}",
        // tech: {} //^ created by ip_to_geo add-on
        user_agent: "${keen.user_agent}"
                // visitor: {} //^ created by ua_parser add-on
    },
    keen: {
        timestamp: new Date().toISOString(),
        addons: [
            {name: "keen:ip_to_geo", input: {ip: "visitor.ip_address"}, output: "visitor.geo"},
            {name: "keen:ua_parser", input: {ua_string: "visitor.user_agent"}, output: "visitor.tech"}
        ]
    }
};

ga(function (tracker) {
    $keenData.clientId = tracker.get("clientId");
});

$(function () {
    var pathname = document.location.pathname;

    //if (document.location.host === 'app.planodesaude.net.br') {


    if (pathname === "/cadastro/inicial") {

        $('#user').on('submit', function () {

            var $cliente = {
                client_id: $keenData.clientId,
                home: $('#nome').val(),
                login: $('#login').val(),
                email_usuario: $('#email_usuario').val(),
                telefone: $('#telefone').val(),
                perfil: $('#perfil').val()
            };

            client.addEvent("CadastroClient", $cliente);
            localStorage.setItem("DataCliente.email_usuario", $('#email_usuario').val());
        });

    }
    if (pathname.indexOf("cadastro/formulario") > 0) {

        $('#user').on('submit', function () {
            $co = {
                client_id: $keenData.clientId,
                id_usuario: $('#id_usuario').val(),
                estado: $('#estados option:selected').text(),
                cidade: $('#cidades option:selected').text(),
                operadora: $('#operadoras option:selected').text(),
                email_usuario: localStorage.getItem('DataCliente.email_usuario')
            };

            console.log($co);

            client.addEvent("CadastroOperadora", $co);
//
//
        });

    }

    if (pathname.indexOf("cadastro/formulariob") > 0) {

        $('#user').on('submit', function () {

            client.addEvent("CadastroStep3", {
                client_id: $keenData.clientId,
                id_usuario: $('#id_usuario').val(),
                cpf_vendedor: $('#cpf_vendedor').val(),
                rg_vendedor: $('#rg_vendedor').val(),
                dt_nascimento: $('#dt_nascimento').val(),
                ds_logradouro: $('#ds_logradouro').val(),
                nr_cep: $('#nr_cep').val(),
                site_vendedor: $('#site_vendedor').val(),
                email_usuario: localStorage.getItem('DataCliente.email_usuario')
            });


        });
    }


    //}


});

function AtivarVendedorPlanoGratuito(ob) {

    var id_usuario = $(ob).attr('id_usuario');

    client.addEvent("VendedorAtivoPlanoGratuito", {
        id_usuario: id_usuario
    });

}

if (document.location.host === 'app.planodesaude.net.br') {

    !function () {
        var meta = new Keen({
            projectId: "555f548196773d3304737178",
            writeKey: "d5e6a45536f139fbfbbde98948cf9af4d7025c05a87404ed928814f757348c78640fb050a93a2a72e5cef00bc73deb8ec6345c120ebd363b1185bcd65db2b57ed7947de5427fac7a5800f015636dbec2609859b57404d1e0c6e4b2d821ed63050fc73aed38e2c5d3bb394618644aeefb"
        });

        meta.addEvent("visits", $keenData);

    }();

}

