// ������� �� ������� � ����� ������ ��������
function go(str, that)
{
	that.action = str;
	that.submit();
}
// ��������� ����� ��� ��������� ������� ���� �� ����������� ������
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

// ��������� �� ������ �������
function X(that, bclr)
{
	that.style.background = bclr;
}