<?php

class SupabaseClient {
    private $url;
    private $key;

    public function __construct($url, $key) {
        $this->url = rtrim($url, '/');
        $this->key = $key;
    }

    private function request($method, $path, $data = null, $headers = []) {
        $url = $this->url . '/rest/v1/' . ltrim($path, '/');
        $ch = curl_init($url);

        $defaultHeaders = [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($defaultHeaders, $headers));

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $status,
            'data' => json_decode($response, true)
        ];
    }

    public function from($table) {
        return new SupabaseQueryBuilder($this, $table);
    }

    public function executeRaw($method, $path, $data = null) {
        return $this->request($method, $path, $data);
    }

    // Helper for direct SQL via RPC if needed (requires setting up a function in Supabase)
    public function rpc($function, $params = []) {
        return $this->request('POST', 'rpc/' . $function, $params);
    }
}

class SupabaseQueryBuilder {
    private $client;
    private $table;
    private $filters = [];
    private $select = '*';
    private $order = '';
    private $limit = '';

    public function __construct($client, $table) {
        $this->client = $client;
        $this->table = $table;
    }

    public function select($columns = '*') {
        $this->select = $columns;
        return $this;
    }

    public function eq($column, $value) {
        $this->filters[] = $column . '=eq.' . urlencode($value);
        return $this;
    }

    public function order($column, $ascending = true) {
        $this->order = 'order=' . $column . '.' . ($ascending ? 'asc' : 'desc');
        return $this;
    }

    public function limit($count) {
        $this->limit = 'limit=' . $count;
        return $this;
    }

    public function get() {
        $path = $this->table . '?select=' . $this->select;
        if (!empty($this->filters)) {
            $path .= '&' . implode('&', $this->filters);
        }
        if ($this->order) $path .= '&' . $this->order;
        if ($this->limit) $path .= '&' . $this->limit;

        $res = $this->client->executeRaw('GET', $path);
        return $res['data'];
    }

    public function insert($data) {
        $res = $this->client->executeRaw('POST', $this->table, $data);
        return $res['data'];
    }

    public function update($data) {
        $path = $this->table;
        if (!empty($this->filters)) {
            $path .= '?' . implode('&', $this->filters);
        }
        $res = $this->client->executeRaw('PATCH', $path, $data);
        return $res['data'];
    }

    public function delete() {
        $path = $this->table;
        if (!empty($this->filters)) {
            $path .= '?' . implode('&', $this->filters);
        }
        $res = $this->client->executeRaw('DELETE', $path);
        return $res['data'];
    }
}
