<?php $req = Request::create("/cursos?gestion=1", "GET"); $res = app()->handle($req); echo $res->getStatusCode();
