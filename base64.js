function base64_encode(a)
	{
		var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'.split('');
		var i, n = a.length, tmp = 0, s = Array();
		i = j = 0;
		while (i < n)
			{
				tmp = (tmp << 8) | a[i];
				if (i % 3 == 2)
					{
						s[s.length] = chars[(tmp >> 18) & 0x3f];
						s[s.length] = chars[(tmp >> 12) & 0x3f];
						s[s.length] = chars[(tmp >> 6) & 0x3f];
						s[s.length] = chars[tmp & 0x3f];
						j += 4;
						if (j % 76 == 0) s[s.length] = '\r\n';
						tmp = 0;
					}
				i++;
			}
		
		switch (i % 3)
			{
				case 1 :
					s[s.length] = chars[(tmp >> 2) & 0x3f];
					s[s.length] = chars[(tmp << 4) & 0x3f];
					s[s.length] = '=';
					s[s.length] = '=';
				break;
				case 2 :
					s[s.length] = chars[(tmp >> 10) & 0x3f];
					s[s.length] = chars[(tmp >> 4) & 0x3f];
					s[s.length] = chars[(tmp << 2) & 0x3f];
					s[s.length] = '=';
				break;
			}
		return s.join('');
	}

function base64_decode(s)
	{
		s = s.split('');
		var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'.split('');
		var i, j, codes = Object(), a = Array(), n = s.length, tmp = 0;
		for (i = 0; i < 64; i++) codes[chars[i]] = i; // формируем таблицу кодов
		i = j = 0;
		while (i < n)
			{
				while (i < n && isNaN(codes[s[i]]) && s[i] != '=') i++; // пропускаем неверные символы
				if (i == n || s[i] == '=') break;
				tmp = (tmp << 6) | codes[s[i]];
				if (j % 4 == 3)
					{
						a[a.length] = (tmp >> 16) & 0xff;
						a[a.length] = (tmp >> 8) & 0xff;
						a[a.length] = tmp & 0xff;
						tmp = 0;
					}
				j++;
				i++;
			}
		switch (j % 4)
			{
			case 2 :
				a[a.length] = (tmp >> 4) & 0xff;
			break;
			case 3 :
				a[a.length] = (tmp >> 10) & 0xff;
				a[a.length] = (tmp >> 2) & 0xff;
			break;
			}
		return a;
	}

function utf8_encode(s) {
var n = s.length, i, a = Array(), ch;
for (i = 0; i < n; i++) {
ch = s.charCodeAt(i);
if (!(ch >> 7)) a[a.length] = ch; // один байт
else if (!(ch >> 11)) {
// два байта
a[a.length] = 0xc0 | (ch >> 6);
a[a.length] = 0x80 | (ch & 0x3f);
} else if (!(ch >> 16)) {
// три байта
a[a.length] = 0xe0 | (ch >> 12);
a[a.length] = 0x80 | ((ch >> 6) & 0x3f);
a[a.length] = 0x80 | (ch & 0x3f);
} else {
// четыре байта
a[a.length] = 0xf0 | (ch >> 18);
a[a.length] = 0x80 | ((ch >> 12) & 0x3f);
a[a.length] = 0x80 | ((ch >> 6) & 0x3f);
a[a.length] = 0x80 | (ch & 0x3f);
}
}
return a;
}

function utf8_decode(a) {
var n = a.length, i, s = '', ch;
i = 0;
while (i < n) {
if (!(a[i] & 0x80)) ch = a[i]; // один байт
else if (i + 1 < n && !((a[i + 1] & 0xc0) ^ 0x80)) {
if (!((a[i] & 0xe0) ^ 0xc0)) {
ch = ((a[i] & 0x1f) << 6) | (a[i + 1] & 0x3f); // два байта
i++;
}
else if (i + 2 < n && !((a[i + 2] & 0xc0) ^ 0x80)) {
if (!((a[i] & 0xf0) ^ 0xe0)) {
ch = ((a[i] & 0x0f) << 12) | ((a[i + 1] & 0x3f) << 6) | (a[i + 2] & 0x3f); // три байта
i += 2;
}
else if (i + 3 < n && !((a[i + 3] & 0xc0) ^ 0x80)) {
if (!((a[i] & 0xf8) ^ 0xf0)) {
ch = ((a[i] & 0x07) << 18) | ((a[i + 1] & 0x3f) << 12) | ((a[i + 2] & 0x3f) << 6) | (a[i + 3] & 0x3f); // четыре байта
i += 3;
}
else {
// неверный формат символа с индексом i
}
} else {
// неверный формат символа с индексом i + 3
}
} else {
// неверный формат символа с индексом i + 2
}
} else {
// неверный формат символа с индексом i + 1
}
s += String.fromCharCode(ch);
i++;
}
return s;
}