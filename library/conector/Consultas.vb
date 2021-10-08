Imports System.Data.Odbc
Imports System.IO
Imports Newtonsoft.Json
Imports Newtonsoft.Json.Linq

Public Class Consultas
    'Tabla a manipular
    Private tabla As JObject

    'Lista de tablas
    Private listaClonada As List(Of List(Of JObject))
    Private listaModelos As List(Of String)
    Private incluirTabla As Boolean

    'Varaibles para consultas
    Private seleccion As String = ""
    Private consulta As String = ""
    Private join As String = ""
    Private orderBy As String = ""
    Private groupByA As String = ""

    'Inserts, Updates, Delete
    Private insert As String = ""
    Private update As String = ""
    Private delete As String = ""

    'Variables para obtener y manipular objetos JSON
    Private jsonObj As JObject

    'DB
    Private command As OdbcCommand

    Sub New(ByVal tabla As String, Optional incluirTabla As Boolean = True)
        jsonObj = JObject.Parse(Modelos.TextJson())
        Me.tabla = jsonObj("Modelos")(tabla)
        listaModelos = New List(Of String)
        Me.incluirTabla = incluirTabla
        If incluirTabla Then
            listaModelos.Add(tabla)
        End If
    End Sub

    Public Function Seleccionar(Optional seleccionAttrib As String = "*") As Consultas
        If seleccion IsNot "" Then
            Return Nothing
        Else
            seleccion = "SELECT " & seleccionAttrib & " FROM " & tabla("nombreTabla").ToString()
            Return Me
        End If
    End Function
    Public Function Count(ByVal atributo As String, Optional modelo As String = Nothing, Optional extAtributos As String = "") As Consultas
        If seleccion IsNot "" Then
            Return Nothing
        Else
            If modelo IsNot Nothing Then
                seleccion = "SELECT COUNT(" & modelo & "." & atributo & "), " & extAtributos & " FROM " & tabla("nombreTabla").ToString()
            Else
                seleccion = "SELECT COUNT(" & atributo & ") FROM " & tabla("nombreTabla").ToString()
            End If
            Return Me
        End If
    End Function
    Public Function Where(ByVal attrib As String, ByVal valor As String, Optional concat As String = "", Optional ByVal modelo As String = "") As Consultas
        Dim simbolo As String = " = "
        If concat IsNot "" Then
            simbolo = " " & concat & " "
        End If
        If attrib IsNot "" And valor IsNot "" And seleccion IsNot "" Or update IsNot "" Or delete IsNot "" Then
            If consulta Is "" Then
                If modelo IsNot "" Then
                    consulta = " WHERE " & modelo & "." & attrib & simbolo
                Else
                    consulta = " WHERE " & attrib & simbolo
                End If

            Else
                If modelo IsNot "" Then
                    consulta += " AND " & modelo & "." & attrib & simbolo
                Else
                    consulta += " AND " & attrib & simbolo
                End If
            End If
            Dim valorConvertido As String = ""
            Dim tempTabla As JObject = tabla
            If modelo IsNot "" Then
                tempTabla = jsonObj("Modelos")(modelo)
            End If
            For Each atributo As JObject In tempTabla("Atributos")
                If atributo("Nombre").ToString() = attrib Then
                    If atributo("tipo").ToString() = "String" Or atributo("tipo").ToString() = "char" Then
                        valorConvertido = "'" & valor & "'"
                    Else
                        valorConvertido = valor
                    End If
                End If
            Next
            consulta += valorConvertido
            Return Me
        Else
            Return Nothing
        End If
    End Function

    Public Function Con(ByVal attrib As String, ByVal modelo As String, ByVal tipo As String, Optional tablaB As String = "", Optional attribB As String = "") As Consultas
        If attrib IsNot "" And modelo IsNot "" And tipo IsNot "" Then
            If join = "" Then
                If tablaB IsNot "" Then
                    join = " " & tipo & " JOIN " & modelo & " ON " & tablaB & "." & attrib & " = "
                Else
                    join = " " & tipo & " JOIN " & modelo & " ON " & tabla("nombreTabla").ToString() & "." & attrib & " = "
                End If

                If attribB IsNot "" Then
                    join += modelo & "." & attribB
                Else
                    join += modelo & "." & attrib
                End If
            Else
                If tablaB IsNot "" Then
                    join += " " & tipo & " JOIN " & modelo & " ON " & tablaB & "." & attrib & " = "
                Else
                    join += " " & tipo & " JOIN " & modelo & " ON " & tabla("nombreTabla").ToString() & "." & attrib & " = "
                End If

                If attribB IsNot "" Then
                    join += modelo & "." & attribB
                Else
                    join += modelo & "." & attrib
                End If
            End If
            listaModelos.Add(modelo)
            Return Me
        Else
            Return Nothing
        End If
    End Function

    Public Function OrdenarPor(ByVal attrib As String, Optional tipo As String = "") As Consultas
        If attrib IsNot "" Then
            If orderBy IsNot "" Then
                orderBy += ", " & attrib
            Else
                orderBy = " ORDER BY " & attrib
            End If
            orderBy += " " & tipo
            Return Me
        Else
            Return Nothing
        End If
    End Function

    Public Function GroupBy(ByVal atributos As String) As Consultas
        groupByA = atributos
        Return Me
    End Function

    'Inserts
    Public Function Guardar(ByVal datos As JArray) As Boolean
        Dim datosInternos As String = ""
        Dim orden As String = ""
        For Each dato As JObject In datos
            If dato("Nombre").ToString() = datos(datos.Count - 1)("Nombre").ToString() Then
                orden += dato("Nombre").ToString()
            Else
                orden += dato("Nombre").ToString() & ", "
            End If
            Dim valorConvertido As String = ""
            If Not dato("valor").Type = JTokenType.Null And dato("tipo").ToString() = "String" Or dato("tipo").ToString() = "char" Then
                valorConvertido = "'" & dato("valor").ToString() & "'"
            ElseIf dato("valor").Type = JTokenType.Null Then
                valorConvertido = "NULL"
            Else
                valorConvertido = dato("valor").ToString()
            End If
            If datosInternos IsNot "" Then
                datosInternos += ", " & valorConvertido
            Else
                datosInternos = valorConvertido
            End If
        Next
        insert = "INSERT INTO " & tabla("nombreTabla").ToString() & "(" & orden & ") VALUES("
        insert += datosInternos & ")"
        Dim con As Conexion = New Conexion()
        con.Crear()
        Console.WriteLine(insert)
        Try
            command = New OdbcCommand(insert, con.Obtener())
            Dim temp As Integer = command.ExecuteNonQuery()
            If temp > 0 Then
                Return True
            Else
                Return False
            End If
        Catch ex As OdbcException
            Console.WriteLine(ex.Message)
            Return False
        End Try

    End Function

    'Update
    Public Function Actualizar(ByVal datos As JArray) As Consultas
        update = "UPDATE " & tabla("nombreTabla").ToString() & " SET "
        Dim datosInternos As String = ""
        datos.Remove(datos(0))
        For Each dato As JObject In datos
            datosInternos = DatoConvertidoActualizar(datosInternos, dato)
        Next
        update += datosInternos
        Return Me
    End Function

    Private Function DatoConvertidoActualizar(ByVal datosInternos As String, ByVal dato As JObject) As String
        Dim valorConvertido As String = ""
        If Not dato("valor").Type = JTokenType.Null And dato("tipo").ToString() = "String" Or dato("tipo").ToString() = "char" Then
            valorConvertido = "'" & dato("valor").ToString() & "'"
        ElseIf dato("valor").Type = JTokenType.Null Then
            valorConvertido = "NULL"
        ElseIf dato("tipo").ToString() = "Decimal" Then
            valorConvertido = dato("valor").ToString().Replace(",", ".")
        Else
            valorConvertido = dato("valor").ToString()
        End If

        If datosInternos IsNot "" Then
            datosInternos += ", " & dato("Nombre").ToString() & "=" & valorConvertido
        Else
            datosInternos = dato("Nombre").ToString() & "=" & valorConvertido
        End If
        Return datosInternos
    End Function

    'Delete
    Public Function Eliminar(Optional logica As Boolean = False) As Consultas
        If logica = False Then
            delete = "DELETE FROM " & tabla("nombreTabla").ToString()
        Else
            delete = "UPDATE " & tabla("nombreTabla").ToString() & " SET eliminado=1"
        End If
        Return Me
    End Function


    Public Function Ejecutar() As Boolean
        Dim con As Conexion = New Conexion()
        con.Crear()
        listaClonada = New List(Of List(Of JObject))
        Dim ConsultaEntera As String = ""
        Dim odbcReader As OdbcDataReader
        If update IsNot "" Then
            ConsultaEntera = update & consulta
            Console.WriteLine(ConsultaEntera)
            Try
                command = New OdbcCommand(ConsultaEntera, con.Obtener())
                If command.ExecuteNonQuery() > 0 Then
                    Borrar()
                    Return True
                Else
                    Borrar()
                    Return False
                End If
            Catch ex As OdbcException
                Console.WriteLine(ex.Message)
            End Try
        ElseIf delete IsNot "" Then
            ConsultaEntera = delete & consulta
            Try
                command = New OdbcCommand(ConsultaEntera, con.Obtener())
                If command.ExecuteNonQuery() > 0 Then
                    Borrar()
                    Return True
                Else
                    Borrar()
                    Return False
                End If
            Catch ex As OdbcException
                Console.WriteLine(ex.Message)
            End Try
        Else
            ConsultaEntera = seleccion & join & consulta & orderBy & groupByA
            Console.WriteLine(ConsultaEntera)
            Try
                command = New OdbcCommand(ConsultaEntera, con.Obtener())
                odbcReader = command.ExecuteReader()
                Dim resultados As List(Of List(Of JObject)) = New List(Of List(Of JObject))
                If odbcReader.HasRows Then
                    While odbcReader.Read()
                        Dim lista As List(Of JObject) = New List(Of JObject)
                        For Each tablaSeleccionada As String In listaModelos
                            Dim tab As JObject = jsonObj("Modelos")(tablaSeleccionada)
                            Dim copia As JObject = tab.DeepClone()
                            For i As Integer = 0 To odbcReader.FieldCount - 1
                                For Each atrib As JObject In copia("Atributos")
                                    If odbcReader.GetName(i) = CType(atrib("Nombre"), String) Then
                                        If Not odbcReader.IsDBNull(i) Then
                                            Select Case atrib("tipo")
                                                Case "Integer"
                                                    atrib("valor") = odbcReader.GetInt32(i)
                                                Case "Boolean"
                                                    atrib("valor") = odbcReader.GetBoolean(i)
                                                Case "String"
                                                    atrib("valor") = odbcReader.GetString(i)
                                                Case "Char"
                                                    atrib("valor") = odbcReader.GetChar(i)
                                                Case "Decimal"
                                                    atrib("valor") = odbcReader.GetDecimal(i).ToString()
                                            End Select
                                        Else
                                            atrib("valor") = Nothing
                                        End If
                                    End If
                                Next
                            Next
                            lista.Add(copia)
                        Next
                        resultados.Add(lista)
                    End While
                    Borrar()
                    listaClonada = resultados
                    odbcReader.Close()
                    con.Cerrar()
                    Return True
                Else
                    Borrar()
                    odbcReader.Close()
                    con.Cerrar()
                    Return False
                End If
            Catch ex As Exception
                Borrar()
                Console.WriteLine(ex.StackTrace)
                Console.WriteLine(ex.Message)
            End Try
        End If
        Return False
    End Function

    Public Function Obtener() As List(Of List(Of JObject))
        Borrar()
        Return listaClonada
    End Function

    Public Function ReturnTabla() As JObject
        Return CType(tabla.DeepClone(), JObject)
    End Function

    Private Sub Borrar()
        listaModelos.Clear()
        If incluirTabla Then
            listaModelos.Add(tabla("nombreTabla"))
        End If
        'Varaibles para consultas
        seleccion = ""
        consulta = ""
        join = ""
        orderBy = ""

        'Inserts, Updates, Delete
        insert = ""
        update = ""
        delete = ""
    End Sub

End Class
