
/* DATOS PARA LA ENTRADA:

    -TITOL event
    -IMATGE event

    -DATA data
    -HORA data

    -LLOC localitzacio
    -ACRECA localitzacio
    -LOCALITAT localitzacio

    -DESCRIPCIO zona
*/

SELECT e.TITOL, e.IMATGE , d.DATA, d.HORA, l.LLOC, l.ACRECA, l.LOCALITAT, z.DESCRIPCIO
    from ENTRADA as t 
        inner join EVENT as e on t.event_id = e.id 
        inner join DATA as d on t.data_id = d.id
        inner join LOCALITZACIO as l on t.loc_id = l.id
        inner join ZONA as z on t.zona_id = z.id
    
        where t.ID="24831KRGX5YM14";

SELECT e.TITOL, e.SUTBITOL , d.DATA, d.HORA,l.LLOC
    from ENTRADA as t 
        inner join EVENT as e on t.event_id = e.id 
        inner join LOCALITZACIO as l on t.loc_id = l.id
        inner join DATA as d on t.data_id = d.id
    
        where d.DATA="15/04/2020" 
        ;