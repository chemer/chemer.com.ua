/*
 * возвращает cookie с именем name, если есть, если нет, то undefined
 */
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
/*
options = {
    expires - Время истечения cookie. Интерпретируется по-разному, в зависимости от типа:
        Число — количество секунд до истечения.
        Объект типа Date — дата истечения.
        Если expires в прошлом, то cookie будет удалено.
        Если expires отсутствует или 0, то cookie будет установлено как сессионное и исчезнет при закрытии браузера.
    path - Путь для cookie.
    domain - Домен для cookie.
    secure - Если true, то пересылать cookie только по защищенному соединению.
}
*/
function setCookie(name, value, options) {
    options = options || {};
    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires*1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) { 
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);
    var updatedCookie = name + "=" + value;

    for(var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];    
        if (propValue !== true) { 
            updatedCookie += "=" + propValue;
        }
    }
    document.cookie = updatedCookie;
}

/*
 * удаляет cookie с именем name
 */
function deleteCookie(name) {
    setCookie(name, "", { expires: -1 });
}

/*
 * получение всех куков в объект
 */
function getCookies(){
	if( !document.cookie || document.cookie.length < 2) {
        return {};
    }

	var res = {},
        coo,
		cArr = document.cookie.split(/;\s?/);
	
    for (var i = 0; i < cArr.length; i++) {
		coo = cArr[i].split('=');
		res[coo[0]] = decodeURIComponent(coo[1]);
	}
	return res;
};

function scrollToElement(theElement) {
    if ($("#"+theElement).size() > 0) {
	    $("html, body").animate({scrollTop: $("#"+theElement).offset().top }, 500);
    }
}

function set_url(str) {
    if (str.search(new RegExp("^https?://","i"), str) != -1) {
        return str;
    } else {
        return str.replace(new RegExp("^([.]*/*)*(.*)","i"), base_url+"$2");
    }
}

