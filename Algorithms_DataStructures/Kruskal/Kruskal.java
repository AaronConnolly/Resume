//Implementation of Kruskal's MST algorithm on a weighted graph

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.Arrays;

class Edge implements Comparable<Edge> {
    int src, dest, weight;

    @Override
    public int compareTo(Edge compareEdge) {
        return this.weight - compareEdge.weight;
    }
}

class Subset {
    int parent, rank;
}

class Graph {
    int V, E;    // V-> no. of vertices & E->no.of edges
    Edge[] edge; // collection of all edges

    // Constructor to initialize the graph from a file
    public Graph(String graphFile) throws IOException {
        FileReader fr = new FileReader(graphFile);
        BufferedReader reader = new BufferedReader(fr);

        String[] parts = reader.readLine().split(" +");
        V = Integer.parseInt(parts[0]);
        E = Integer.parseInt(parts[1]);
        edge = new Edge[E];

        for (int i = 0; i < E; ++i) {
            parts = reader.readLine().split(" +");
            int src = Integer.parseInt(parts[0]);
            int dest = Integer.parseInt(parts[1]);
            int weight = Integer.parseInt(parts[2]);

            edge[i] = new Edge();
            edge[i].src = src;
            edge[i].dest = dest;
            edge[i].weight = weight;
        }
        reader.close();
    }

    // Utility method to find set of an element i (uses path compression technique)
    int find(Subset[] subsets, int i) {
        if (subsets[i].parent != i) {
            subsets[i].parent = find(subsets, subsets[i].parent);
        }
        return subsets[i].parent;
    }

    // Function that does union of two sets of x and y (uses union by rank)
    void Union(Subset[] subsets, int x, int y) {
        int xroot = find(subsets, x);
        int yroot = find(subsets, y);

        if (subsets[xroot].rank < subsets[yroot].rank) {
            subsets[xroot].parent = yroot;
        } else if (subsets[xroot].rank > subsets[yroot].rank) {
            subsets[yroot].parent = xroot;
        } else {
            subsets[yroot].parent = xroot;
            subsets[xroot].rank++;
        }
    }

    // Function to construct MST using Kruskal's algorithm
    void KruskalMST() {
        Edge[] result = new Edge[V];  // This will store the resultant MST
        int e = 0;  // Index variable, used for result[]
        int i = 0;  // Index variable, used for sorted edges
        int totalweight = 0; // Used to calculate total weight of MST

        Arrays.sort(edge);  // Sort all the edges in non-decreasing order of their weight

        Subset[] subsets = new Subset[V];
        for(i = 0; i < V; ++i) {
            subsets[i] = new Subset();
            subsets[i].parent = i;
            subsets[i].rank = 0;
        }

        i = 0;  // Index used to pick next edge
        while (e < V - 1 && i < E) {
            Edge next_edge = edge[i++];

            int x = find(subsets, next_edge.src - 1);  
            int y = find(subsets, next_edge.dest - 1);  

            if (x != y) {
                result[e++] = next_edge;
                Union(subsets, x, y);
                totalweight += next_edge.weight; // Add the weight of the edge to the total weight
            }
        }

        System.out.println("Following are the edges in the constructed MST");
        for (i = 0; i < e; ++i) {
            System.out.println(toChar(result[i].src) + " -- " +
                               toChar(result[i].dest) + " == " + result[i].weight);
        }

        // Print the total weight of the MST
        System.out.println("Total weight of MST: " + totalweight);
    }

    // Convert vertex into char for pretty printing
    private char toChar(int u) {
        return (char)(u + 64);  
    }
}

public class Kruskal {
    public static void main(String[] args) throws IOException {
        String fname = "wGraph1.txt";    
        Graph g = new Graph(fname);
        g.KruskalMST();  // Run Kruskal's algorithm and print the MST
    }
}