/*CALCULAR DATOS DEL CRÉDITO ACTUAL Y UTILIZADO EN CLIENTES MANUALMENTE POR ID*/
SET @ID = 1430;
SET @CRED_OTOR = (SELECT SUM(importe) FROM mvillalp_credides.creditogasolina where id_cliente=@ID And tipo='0');

SET @CRED_RECUP= (SELECT SUM(importe) FROM mvillalp_credides.creditogasolina where id_cliente=@ID And tipo='1');

SET @ABONO_RECUP= (SELECT SUM(cantidad) FROM mvillalp_credides.abono WHERE cliente = @ID AND status = 0);

SET @CREDITO_USADO = (@CRED_OTOR - (@CRED_RECUP + @ABONO_RECUP));

UPDATE mvillalp_credides.clientes SET credit_use = @CREDITO_USADO WHERE num_cliente = @ID;
UPDATE mvillalp_credides.clientes SET credit_actual = (credit_otor - credit_use) WHERE num_cliente = @ID;