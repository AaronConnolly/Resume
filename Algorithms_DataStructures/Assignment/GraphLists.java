// Simple weighted graph representation 
// Uses an Adjacency Linked Lists, suitable for sparse graphs

import java.io.*;
import java.util.Scanner;  // Import the Scanner class
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.LinkedList;
import java.util.List;
import java.util.Queue;

class Heap
{
    private int[] a;	   // heap array
    private int[] hPos;	   // hPos[h[k]] == k
    private int[] dist;    // dist[v] = priority of v

    private int N;         // heap size
   
    // The heap constructor gets passed from the Graph:
    //    1. maximum heap size
    //    2. reference to the dist[] array
    //    3. reference to the hPos[] array
    public Heap(int maxSize, int[] _dist, int[] _hPos) 
    {
        N = 0;
        a = new int[maxSize + 1];
        dist = _dist;
        hPos = _hPos;
    }


    public boolean isEmpty() 
    {
        return N == 0;
    }


    public void siftUp( int k) 
    {
        int v = a[k];
        a[0] = 0; // initialize a[0] to be a sentinel
    
        while (dist[v] < dist[a[k / 2]]) 
        {
            a[k] = a[k / 2];
            hPos[a[k]] = k;
            k /= 2;
        }
        a[k] = v;
        hPos[v] = k;
    }


    public void siftDown( int k) 
    {
        int v, j;
        v = a[k];  
        j = 2 * k;

        while (j <= N) {
            if (j < N && dist[a[j]] > dist[a[j + 1]]) {
                j++;
            }
            if (dist[v] <= dist[a[j]]) {
                break;
            }
            a[k] = a[j];
            hPos[a[k]] = k;
            k = j;
            j *= 2;
        }
        a[k] = v;
        hPos[v] = k;
    }


    public void insert( int x) 
    {
        a[++N] = x;
        siftUp( N);
    }


    public int remove() 
    {   
        int v = a[1];
        hPos[v] = 0; // v is no longer in heap
        a[N+1] = 0;  // put null node into empty spot
        
        a[1] = a[N--];
        siftDown(1);
        
        return v;
    }

}

class Graph {
    class Node {
        public int vert;
        public int wgt;
        public Node next;
    }
    
    // V = number of vertices
    // E = number of edges
    // adj[] is the adjacency lists array
    private int V, E;
    private Node[] adj;
    private Node z;
    private int[] mst;
    
    // used for traversing graph
    private int[] visited;
    
    // default constructor
    public Graph(String graphFile)  throws IOException
    {
        int u, v;
        int e, wgt;
        Node t;

        FileReader fr = new FileReader(graphFile);
		BufferedReader reader = new BufferedReader(fr);
	           
        String splits = " +";  // multiple whitespace as delimiter
		String line = reader.readLine();        
        String[] parts = line.split(splits);
        System.out.println("Parts[] = " + parts[0] + " " + parts[1]);
        
        V = Integer.parseInt(parts[0]);
        E = Integer.parseInt(parts[1]);
        
        // create sentinel node
        z = new Node(); 
        z.next = z;
        
        // create adjacency lists, initialised to sentinel node z       
        adj = new Node[V+1];        
        for(v = 1; v <= V; ++v)
            adj[v] = z;               
        
        mst = new int[V+1];
        visited = new int[V + 1];

       // read the edges
        System.out.println("Reading edges from text file");
        for(e = 1; e <= E; ++e)
        {
            line = reader.readLine();
            parts = line.split(splits);
            u = Integer.parseInt(parts[0]);
            v = Integer.parseInt(parts[1]); 
            wgt = Integer.parseInt(parts[2]);
            
            System.out.println("Edge " + toChar(u) + "--(" + wgt + ")--" + toChar(v));   

           
            // code to put edge into adjacency matrix   
            Node newNode = new Node();
            newNode.vert = v;
            newNode.wgt = wgt;
            newNode.next = adj[u];
            adj[u] = newNode;

            newNode = new Node();
            newNode.vert = u;
            newNode.wgt = wgt;
            newNode.next = adj[v];
            adj[v] = newNode;
            
        }	       
    }
   
    // convert vertex into char for pretty printing
    private char toChar(int u)
    {  
        return (char)(u + 64);
    }
    
    // method to display the graph representation
    public void display() {
        int v;
        Node n;
        
        for(v=1; v<=V; ++v){
            System.out.print("\nadj[" + toChar(v) + "] ->" );
            for(n = adj[v]; n != z; n = n.next) 
                System.out.print(" |" + toChar(n.vert) + " | " + n.wgt + "| ->");    
        }
        System.out.println("");
    }
    
	public void MST_Prim(int s)
	{
        int[] dist = new int[V + 1];
        int[] parent = new int[V + 1];
        int[] hPos = new int[V + 1];
        int wgt_sum = 0;
        Node t;
        

        for (int v = 1; v <= V; ++v) {
            dist[v] = Integer.MAX_VALUE;
            parent[v] = 0;
            hPos[v] = 0;
        }

        dist[s] = 0;
        mst[s] = s;

        Heap h = new Heap(V, dist, hPos);
        h.insert(s);

        while (!h.isEmpty()) {
            int v = h.remove();
            dist[v] = -dist[v]; // marks v as now in the MST
            
            // Print the current vertex and the vertex it is connected to
            if (parent[v] != 0) {
                System.out.println("Vertex " + toChar(v) + " is connected to Vertex " + toChar(parent[v]) + " with edge weight = " + (-dist[v]));
                wgt_sum += dist[v];
                mst[v] = parent[v];
            } else {
                System.out.println("Starting vertex: " + toChar(v));
            }

            for (t = adj[v]; t != z; t = t.next) {
                int u = t.vert;
                int wgt = t.wgt;

                if (wgt < dist[u]) {
                    dist[u] = wgt;
                    parent[u] = v;
                    if (hPos[u] == 0) {
                        h.insert(u);
                    } else {
                        h.siftUp(hPos[u]);
                    }
                }
            }
        }
        
        System.out.println("\nWeight of MST = " + -wgt_sum);
        showMST();
                  		
	}
    
    public void showMST()
    {
        System.out.print("\n\nMinimum Spanning tree parent array is:\n");
        for(int v = 1; v <= V; ++v)
            System.out.println(toChar(v) + " -> " + toChar(mst[v]));
        System.out.println("");
    }

    public void SPT_Dijkstra(int s)
    {
        int[] dist = new int[V + 1]; // Array to hold the distance values
        int[] parent = new int[V + 1]; // Array to hold the parent of each vertex in the path
        int[] hPos = new int[V + 1]; // Heap position array
        Heap pq = new Heap(V, dist, hPos); // Priority queue implemented as a heap
        int num = 1;

        // Reset the visited array for this run, marking all vertices as unvisited
        for (int v = 1; v <= V; v++) {
            visited[v] = 0;
        }

        // Initialize all distances as infinite and parent as -1 (indicating no parent)
        for (int v = 1; v <= V; v++) {
            dist[v] = Integer.MAX_VALUE;
            parent[v] = -1;
            hPos[v] = 0; // Initially, no vertices are in the heap
        }

        // Distance of the source vertex from itself is always 0
        dist[s] = 0;

        // Insert source vertex into priority queue
        pq.insert(s);

        System.out.print("Visited vertices in order: ");
        while (!pq.isEmpty()) {
            // Remove the vertex with the minimum distance from the priority queue
            int v = pq.remove();

            if (visited[v] == 1) continue; // Skip if already visited

            // Process all adjacent vertices of the removed vertex
            for (Node temp = adj[v]; temp != z; temp = temp.next) {
                int u = temp.vert;
                int weight = temp.wgt;

                // Relaxation step: Check if a shorter path to u exists through v
                if (dist[v] + weight < dist[u]) {
                    dist[u] = dist[v] + weight;
                    parent[u] = v;

                    // Update the priority queue with the new distance
                    if (hPos[u] == 0) {
                        pq.insert(u);
                    } else {
                        pq.siftUp(hPos[u]);
                    }
                }
            }
            visited[v] = 1; // Mark v as visited after processing all its adjacent vertices

            // Print statement to show the contents of dist and parent for each iteration
            System.out.println("\nCurrent Vertex: " + v);
            System.out.println("dist: " + num + Arrays.toString(dist));
            System.out.println("parent: " + Arrays.toString(parent));
            System.out.print(v + ", ");
            
            num++;
        }
        showSPT(dist, parent, s); // Show the shortest paths and their distances
            
    }

    public void showSPT(int[] dist, int[] parent, int s)
    {
        System.out.println("\nVertex\tDistance from Source\tPath");
        for (int v = 1; v <= V; v++) {
            System.out.print(toChar(v) + "\t\t");

            // Check if the vertex is unreachable from the source
            if (dist[v] == Integer.MAX_VALUE) {
                System.out.println("Unreachable");
                continue;
            } else {
                System.out.print(dist[v] + "\t\t");
            }

            // Backtrack from the vertex to the source to reconstruct the path
            String path = "";
            for (int u = v; u != -1; u = parent[u]) {
                path = toChar(u) + (path.isEmpty() ? "" : " -> " + path);
                if (u == s) break; // Stop if we reach the source
            }

            System.out.println(path);
        }
    }

    public void DepthFirstSearch(int v) {
        // Reset visited array
        for (int i = 1; i <= V; i++) {
            visited[i] = 0;
        }
    
        // Call the recursive helper function to print DFS traversal
        DFSUtil(v);
    }
    
    private void DFSUtil(int v) {
        // Mark the current node as visited
        visited[v] = 1;
        
        System.out.print(toChar(v) + " ");
        
    
        // Collect all neighbors
        List<Node> neighbors = new ArrayList<>();
        for (Node n = adj[v]; n != z; n = n.next) {
            neighbors.add(n);
        }
    
        // Sort neighbors by vertex identifier (which corresponds to alphabetical order)
        Collections.sort(neighbors, new Comparator<Node>() {
            public int compare(Node node1, Node node2) {
                return node1.vert - node2.vert;
            }
        });
    
        // Recur for all the sorted vertices adjacent to this vertex
        for (Node n : neighbors) {
            if (visited[n.vert] == 0) {
                DFSUtil(n.vert);
            }
        }
    }

    public void BreadthFirstSearch(int startVertex) {
        Queue<Integer> queue = new LinkedList<Integer>(); // Create a queue for BFS
    
        // Mark all vertices as not visited
        boolean visited[] = new boolean[V + 1];
    
        // Mark the current node as visited and enqueue it
        visited[startVertex] = true;
        queue.add(startVertex);
    
        while (!queue.isEmpty()) {
            // Dequeue a vertex from the queue and print it
            int v = queue.poll();
            System.out.print(toChar(v) + " ");
    
            // Get all adjacent vertices of the dequeued vertex v
            // If an adjacent has not been visited, then mark it visited and enqueue it in sorted order
            List<Node> neighbors = new ArrayList<>();
    
            // Collect all neighbors
            for (Node temp = adj[v]; temp != z; temp = temp.next) {
                neighbors.add(temp);
            }
    
            // Sort neighbors by vertex identifier (which corresponds to alphabetical order)
            Collections.sort(neighbors, (node1, node2) -> node1.vert - node2.vert);
    
            // Enqueue all sorted neighbors that have not been visited
            for (Node neighbor : neighbors) {
                int adjVertex = neighbor.vert;
                if (!visited[adjVertex]) {
                    visited[adjVertex] = true;
                    queue.add(adjVertex);
                }
            }
        }
        System.out.println();
    }

}

public class GraphLists {
    public static void main(String[] args) throws IOException
    {
        int user_vertex = 12;
        int option;
        Scanner myObj = new Scanner(System.in); // create a scanner object

        System.out.println("Enter FileName");    
        String fname = myObj.nextLine();  // Read user input    

        System.out.println("Enter starting vertex;");    
        user_vertex = myObj.nextInt();  // Read user input 

        
           
        Graph g = new Graph(fname);
       
        g.display();
        do
        {
            System.out.println("\n1. Prim's MST\n2. Dijkstra SPT\n3. Depth First\n4. Breadth First\n5. Exit");
            Scanner user_choice = new Scanner(System.in);
            option = user_choice.nextInt();
            switch(option)  // Menu
            {
                case 1:
                    g.MST_Prim(user_vertex);
                    break;
                case 2:
                    g.SPT_Dijkstra(user_vertex);
                    break;
                case 3:
                    g.DepthFirstSearch(user_vertex);
                    break;
                case 4:
                    g.BreadthFirstSearch(user_vertex);
                    break;
                default:
                System.out.println("Error: Not an option, try again");
            }
        }
        while (option != 5);
        
    }
}
