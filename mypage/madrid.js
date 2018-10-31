// Переход по ссылкам в шапке каждой страницы
function go(str, that)
{
	that.action = str;
	that.submit();
}
// Изменение цвета при наведении курсора мыши на определённую кнопку
function MOver(that)
{
	that.style.color = '#741147';
	that.style.cursor = 'pointer';
}
function MOut(that)
{
	that.style.color = '#147741';
	that.style.cursor = 'default';
}

// Наведение на строку таблицы
function X(that, bclr)
{
	that.style.background = bclr;
}