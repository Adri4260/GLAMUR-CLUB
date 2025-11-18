<?php
// includes/json_connect.php
// Funciones para comunicarse con JSON Server

require_once __DIR__ . '/config.php';

function make_request($method, $endpoint, $data = null) {
    $url = JSON_SERVER_URL . $endpoint;
    
    $options = [
        'http' => [
            'method' => $method,
            'header' => 'Content-Type: application/json',
            'ignore_errors' => true
        ]
    ];
    
    if ($data !== null && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        $options['http']['content'] = json_encode($data);
    }
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    $status = 500;
    if (isset($http_response_header[0])) {
        preg_match('/\d{3}/', $http_response_header[0], $matches);
        $status = isset($matches[0]) ? (int)$matches[0] : 500;
    }
    
    return [
        'status' => $status,
        'data' => $response ? json_decode($response, true) : null
    ];
}

function find_user_by_username($nom_usuari) {
    $response = make_request('GET', '/usuaris?nom_usuari=' . urlencode($nom_usuari));
    
    if ($response['status'] === 200 && !empty($response['data'])) {
        return $response['data'][0];
    }
    
    return null;
}

function find_user_by_id($id) {
    $response = make_request('GET', '/usuaris/' . $id);
    
    if ($response['status'] === 200 && $response['data']) {
        return $response['data'];
    }
    
    return null;
}

function create_user($data) {
    return make_request('POST', '/usuaris', $data);
}

function update_user($id, $data) {
    return make_request('PATCH', '/usuaris/' . $id, $data);
}

function delete_user($id) {
    return make_request('DELETE', '/usuaris/' . $id);
}
